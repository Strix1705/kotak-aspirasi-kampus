<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $override = []): array
    {
        return array_merge([
            'name' => 'Mahasiswa Baru',
            'nim' => '220101099',
            'email' => 'baru@student.kampus.ac.id',
            'password' => 'Rahasia#123',
            'password_confirmation' => 'Rahasia#123',
        ], $override);
    }

    public function test_register_page_renders(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('name="name"', false)
            ->assertSee('name="nim"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('_token', false);
    }

    public function test_guest_redirected_from_dashboard_can_reach_register(): void
    {
        $this->get('/mahasiswa/dashboard')->assertRedirect('/login');
        $this->get('/register')->assertOk();
    }

    public function test_successful_registration_creates_mahasiswa_and_logs_in(): void
    {
        $response = $this->post('/register', $this->validPayload());

        $response->assertRedirect('/mahasiswa/dashboard');
        $response->assertSessionHas('success');

        $user = User::where('email', 'baru@student.kampus.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertSame('mahasiswa', $user->role);
        $this->assertSame('220101099', $user->nim);
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_is_bcrypt_hashed_in_database(): void
    {
        $this->post('/register', $this->validPayload());

        $user = User::where('email', 'baru@student.kampus.ac.id')->first();

        $this->assertTrue(str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$2a$'));
        $this->assertNotSame('Rahasia#123', $user->password);
        $this->assertTrue(Hash::check('Rahasia#123', $user->password));
    }

    public function test_role_cannot_be_injected_by_user(): void
    {
        // User jahat coba kirim role=admin lewat request
        $this->post('/register', $this->validPayload(['role' => 'admin']));

        $user = User::where('email', 'baru@student.kampus.ac.id')->first();
        $this->assertSame('mahasiswa', $user->role);
    }

    public function test_duplicate_nim_is_rejected(): void
    {
        User::create([
            'name' => 'Pemilik NIM',
            'nim' => '220101099',
            'email' => 'lama@student.kampus.ac.id',
            'password' => Hash::make('Secret#123'),
            'role' => 'mahasiswa',
        ]);

        $this->post('/register', $this->validPayload())
            ->assertSessionHasErrors('nim');

        $this->assertSame(1, User::count());
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::create([
            'name' => 'Pemilik Email',
            'nim' => '220101088',
            'email' => 'baru@student.kampus.ac.id',
            'password' => Hash::make('Secret#123'),
            'role' => 'mahasiswa',
        ]);

        $this->post('/register', $this->validPayload())
            ->assertSessionHasErrors('email');
    }

    public function test_short_password_is_rejected(): void
    {
        $this->post('/register', $this->validPayload([
            'password' => 'Ab1',
            'password_confirmation' => 'Ab1',
        ]))->assertSessionHasErrors('password');
    }

    public function test_password_without_uppercase_is_rejected(): void
    {
        $this->post('/register', $this->validPayload([
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]))->assertSessionHasErrors('password');
    }

    public function test_password_without_number_is_rejected(): void
    {
        $this->post('/register', $this->validPayload([
            'password' => 'Rahasiaaa',
            'password_confirmation' => 'Rahasiaaa',
        ]))->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_must_match(): void
    {
        $this->post('/register', $this->validPayload([
            'password_confirmation' => 'Berbeda#123',
        ]))->assertSessionHasErrors('password');
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->post('/register', $this->validPayload([
            'email' => 'bukan-email',
        ]))->assertSessionHasErrors('email');
    }

    public function test_required_fields_are_validated(): void
    {
        $this->post('/register', [])
            ->assertSessionHasErrors(['name', 'nim', 'email', 'password']);
    }

    public function test_xss_in_name_is_escaped_on_output(): void
    {
        $this->post('/register', $this->validPayload([
            'name' => '<script>alert("hacked")</script>',
        ]));

        $response = $this->get('/mahasiswa/dashboard');
        $response->assertOk();
        $response->assertDontSee('<script>alert("hacked")</script>', false);
        $response->assertSee('&lt;script&gt;', false);
    }

    public function test_authenticated_user_cannot_access_register(): void
    {
        $user = User::create([
            'name' => 'Sudah Login',
            'nim' => '220101077',
            'email' => 'sudah@student.kampus.ac.id',
            'password' => Hash::make('Secret#123'),
            'role' => 'mahasiswa',
        ]);

        $this->actingAs($user)->get('/register')->assertRedirect('/mahasiswa/dashboard');
        $this->actingAs($user)->post('/register', $this->validPayload())->assertRedirect('/mahasiswa/dashboard');

        $this->assertSame(1, User::count());
    }

    public function test_nim_is_sanitized_to_uppercase(): void
    {
        $this->post('/register', $this->validPayload(['nim' => '  22ab101  ']));

        $user = User::where('email', 'baru@student.kampus.ac.id')->first();
        $this->assertSame('22AB101', $user->nim);
    }
}
