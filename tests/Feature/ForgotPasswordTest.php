<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Kirim Tautan Reset Kata Sandi');
    }

    public function test_user_can_submit_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', 'Jika email terdaftar, kami telah mengirimkan tautan untuk mengatur ulang password.');

        Notification::assertSentTo($user, CustomResetPassword::class);
    }

    public function test_unregistered_email_receives_generic_response(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'notfound@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', 'Jika email terdaftar, kami telah mengirimkan tautan untuk mengatur ulang password.');

        Notification::assertNothingSent();
    }

    public function test_valid_token_can_open_reset_password_page(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

        $response->assertStatus(200);
        $response->assertSee('Simpan Kata Sandi Baru');
        $response->assertSee($user->email);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status', 'Password berhasil diperbarui. Silakan login menggunakan password baru.');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertFalse(Hash::check('old-password', $user->fresh()->password));

        // Ensure user is not auto-logged in
        $this->assertGuest();
    }

    public function test_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Link reset password sudah tidak valid atau telah kedaluwarsa.']);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_used_token_cannot_be_reused(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);
        $token = Password::createToken($user);

        // First use
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // Second use attempt
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newer-password',
            'password_confirmation' => 'newer-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Link reset password sudah tidak valid atau telah kedaluwarsa.']);
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
