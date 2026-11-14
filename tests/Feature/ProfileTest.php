<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $override = []): User
    {
        return User::create(array_merge([
            'name' => 'User Tes',
            'nim' => '220109999',
            'email' => 'user@test.ac.id',
            'password' => Hash::make('Secret#123'),
            'role' => 'mahasiswa',
        ], $override));
    }

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/profile')->assertRedirect('/login');
        $this->patch('/profile/update', ['name' => 'Nama Baru'])->assertRedirect('/login');
        $this->patch('/profile/password', [])->assertRedirect('/login');
    }

    public function test_user_can_view_profile_page(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk()
            ->assertSee('value="User Tes"', false)
            ->assertSee('value="user@test.ac.id"', false)
            ->assertSee('readonly', false);
    }

    public function test_user_can_update_name(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->patch('/profile/update', [
            'name' => 'Nama Baru Terverifikasi',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Nama Baru Terverifikasi', $user->name);
    }

    public function test_xss_in_name_is_sanitized_or_rejected(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->patch('/profile/update', [
            'name' => '<script>alert("hack")</script> Budi',
        ]);

        $user->refresh();
        $this->assertStringNotContainsString('<script>', $user->name);
        $this->assertSame('alert("hack") Budi', $user->name);
    }

    public function test_name_cannot_contain_bracket_tags(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->patch('/profile/update', [
            'name' => '<div>Tag</div>',
        ]);

        $user->refresh();
        $this->assertStringNotContainsString('<div>', $user->name);
    }

    public function test_idor_prevention_always_updates_logged_in_user(): void
    {
        $victim = $this->makeUser(['email' => 'victim@test.ac.id', 'nim' => '11111']);
        $attacker = $this->makeUser(['email' => 'attacker@test.ac.id', 'nim' => '22222']);

        // Attacker coba kirim user_id milik victim
        $this->actingAs($attacker)->patch('/profile/update', [
            'user_id' => $victim->id,
            'name' => 'Nama Peretas',
        ]);

        $victim->refresh();
        $attacker->refresh();

        $this->assertNotSame('Nama Peretas', $victim->name);
        $this->assertSame('Nama Peretas', $attacker->name);
    }

    public function test_user_can_update_password_with_correct_current_password(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->patch('/profile/password', [
            'current_password' => 'Secret#123',
            'password' => 'PassBaru#2026',
            'password_confirmation' => 'PassBaru#2026',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('PassBaru#2026', $user->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->patch('/profile/password', [
            'current_password' => 'PasswordSalah',
            'password' => 'PassBaru#2026',
            'password_confirmation' => 'PassBaru#2026',
        ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('Secret#123', $user->password));
    }

    public function test_new_password_without_uppercase_is_rejected(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->patch('/profile/password', [
            'current_password' => 'Secret#123',
            'password' => 'passbaru2026',
            'password_confirmation' => 'passbaru2026',
        ])->assertSessionHasErrors('password');
    }

    public function test_new_password_without_numbers_is_rejected(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->patch('/profile/password', [
            'current_password' => 'Secret#123',
            'password' => 'PassBaruuuu',
            'password_confirmation' => 'PassBaruuuu',
        ])->assertSessionHasErrors('password');
    }

    public function test_admin_can_access_and_update_profile(): void
    {
        $admin = $this->makeUser(['role' => 'admin', 'email' => 'admin2@test.ac.id', 'nim' => 'ADM99']);

        $this->actingAs($admin)->get('/profile')->assertOk();

        $this->actingAs($admin)->patch('/profile/update', [
            'name' => 'Admin Baru',
        ])->assertRedirect('/profile');

        $admin->refresh();
        $this->assertSame('Admin Baru', $admin->name);
    }
}
