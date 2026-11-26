<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_private_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
        $this->get('/profile/password')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/courses/create')->assertRedirect('/login');
        $this->post('/courses', [])->assertRedirect('/login');
    }

    public function test_public_routes_remain_accessible_for_guests(): void
    {
        $course = Course::create([
            'code' => 'PUB-01',
            'name' => 'Kursus Publik',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $this->get('/')->assertStatus(200);
        $this->get('/courses')->assertStatus(200)->assertDontSee('Tambah Kursus');
        $this->get('/courses/' . $course->id)->assertStatus(200)->assertDontSee('Hapus');
        $this->get('/enrollments/create')->assertStatus(200);
        $this->get('/pendaftaran')->assertRedirect('/enrollments/create');
    }

    public function test_regular_user_cannot_access_admin_or_course_write_routes(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $course = Course::create([
            'code' => 'CRS-01',
            'name' => 'Kursus 1',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $this->actingAs($user);

        $this->get('/dashboard')->assertStatus(200);
        $this->get('/admin')->assertStatus(403);
        $this->get('/admin/users')->assertStatus(403);
        $this->get('/courses/create')->assertStatus(403);
        $this->post('/courses', [])->assertStatus(403);
        $this->get('/courses/' . $course->id . '/edit')->assertStatus(403);
        $this->delete('/courses/' . $course->id)->assertStatus(403);
    }

    public function test_user_cannot_escalate_role_via_profile_update(): void
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@kursusku.test',
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)->put('/profile', [
            'name' => 'Updated User',
            'email' => 'regular@kursusku.test',
            'role' => 'admin',
            'is_active' => 0,
        ]);

        $fresh = $user->fresh();
        $this->assertSame('user', $fresh->role);
        $this->assertTrue($fresh->is_active);
    }

    public function test_deactivated_user_is_logged_out_by_active_middleware(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => false]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
