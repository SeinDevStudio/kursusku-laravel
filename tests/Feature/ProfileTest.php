<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_and_upload_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Old Name',
            'username' => 'olduser',
            'email' => 'old@kursusku.test',
            'role' => 'user',
            'is_active' => true,
        ]);

        $photo = UploadedFile::fake()->image('avatar.png', 300, 300);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'New Name',
            'username' => 'newuser',
            'email' => 'new@kursusku.test',
            'phone' => '081234567890',
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $fresh = $user->fresh();
        $this->assertSame('New Name', $fresh->name);
        $this->assertSame('newuser', $fresh->username);
        $this->assertSame('new@kursusku.test', $fresh->email);
        $this->assertSame('081234567890', $fresh->phone);
        $this->assertNotNull($fresh->profile_photo);
        Storage::disk('public')->assertExists($fresh->profile_photo);
    }

    public function test_user_cannot_upload_non_image_file_as_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $badFile = UploadedFile::fake()->create('script.php', 10, 'application/x-php');

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $badFile,
        ]);

        $response->assertSessionHasErrors('profile_photo');
    }

    public function test_user_cannot_use_duplicate_email_or_username(): void
    {
        User::factory()->create([
            'username' => 'takenname',
            'email' => 'taken@kursusku.test',
        ]);

        $user = User::factory()->create([
            'username' => 'myname',
            'email' => 'me@kursusku.test',
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'My Name',
            'username' => 'takenname',
            'email' => 'taken@kursusku.test',
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }

    public function test_user_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldSecret123!'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'OldSecret123!',
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ]);

        $response->assertRedirect('/profile/password');
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewSecret123!', $user->fresh()->password));
    }

    public function test_change_password_fails_when_current_password_is_wrong(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldSecret123!'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'WrongOld123!',
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ]);

        $response->assertSessionHasErrors('current_password');
    }
}
