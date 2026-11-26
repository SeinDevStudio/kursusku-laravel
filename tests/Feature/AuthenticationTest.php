<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible_by_guest(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('name="remember"', false);
    }

    public function test_user_can_login_and_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@kursusku.test',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'user@kursusku.test',
            'password' => 'SecretPass123!',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@kursusku.test',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@kursusku.test',
            'password' => 'AdminPass123!',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'user@kursusku.test',
            'password' => Hash::make('CorrectPass123!'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'user@kursusku.test',
            'password' => 'WrongPass123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'inactive@kursusku.test',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'user',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@kursusku.test',
            'password' => 'SecretPass123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        User::factory()->create([
            'email' => 'brute@kursusku.test',
            'password' => Hash::make('SecretPass123!'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'brute@kursusku.test',
                'password' => 'WrongPass',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'brute@kursusku.test',
            'password' => 'WrongPass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('email'));
    }

    public function test_user_can_logout_and_session_is_invalidated(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_forgot_and_reset_password_flow_works(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@kursusku.test',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $forgotResponse = $this->post('/forgot-password', [
            'email' => 'reset@kursusku.test',
        ]);
        $forgotResponse->assertSessionHas('status');

        $capturedToken = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$capturedToken) {
            $capturedToken = $notification->token;
            return true;
        });

        $this->assertNotNull($capturedToken);

        $resetResponse = $this->post('/reset-password', [
            'token' => $capturedToken,
            'email' => 'reset@kursusku.test',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $resetResponse->assertRedirect('/login');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }
}
