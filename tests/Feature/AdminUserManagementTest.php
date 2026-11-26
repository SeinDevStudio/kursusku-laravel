<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard_and_user_list_with_search_and_filter(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@kursusku.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Alya Rahma',
            'email' => 'alya@kursusku.test',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Bima Nonaktif',
            'email' => 'bima@kursusku.test',
            'role' => 'user',
            'is_active' => false,
        ]);

        $this->actingAs($admin);

        $this->get('/admin')->assertStatus(200)->assertSee('Total Pengguna');

        $search = $this->get('/admin/users?q=Alya&role=user&status=active');
        $search->assertStatus(200);
        $search->assertSee('Alya Rahma');
        $search->assertDontSee('Bima Nonaktif');
    }

    public function test_admin_can_create_edit_and_toggle_user_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->actingAs($admin);

        $createResponse = $this->post('/admin/users', [
            'name' => 'Peserta Baru',
            'username' => 'pesertabaru',
            'email' => 'baru@kursusku.test',
            'phone' => '081122334455',
            'role' => 'user',
            'is_active' => '1',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
        ]);

        $createResponse->assertRedirect('/admin/users');
        $target = User::where('email', 'baru@kursusku.test')->firstOrFail();

        $this->patch('/admin/users/' . $target->id . '/toggle-status');
        $this->assertFalse($target->fresh()->is_active);

        $this->put('/admin/users/' . $target->id, [
            'name' => 'Peserta Diubah',
            'username' => 'pesertabaru',
            'email' => 'baru@kursusku.test',
            'role' => 'admin',
            'is_active' => '1',
        ]);

        $this->assertSame('Peserta Diubah', $target->fresh()->name);
        $this->assertSame('admin', $target->fresh()->role);
        $this->assertTrue($target->fresh()->is_active);
    }

    public function test_admin_cannot_deactivate_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->actingAs($admin);

        $response = $this->patch('/admin/users/' . $admin->id . '/toggle-status');
        $response->assertSessionHasErrors('status');
        $this->assertTrue($admin->fresh()->is_active);
    }
}
