<?php

namespace Tests\Feature;

use App\Models\Aspiration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeMahasiswa(string $email = 'mhs@test.ac.id'): User
    {
        return User::create([
            'name' => 'Mahasiswa Tes',
            'nim' => 'T'.random_int(100000, 999999),
            'email' => $email,
            'password' => Hash::make('Secret#123'),
            'role' => 'mahasiswa',
        ]);
    }

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin Tes',
            'nim' => 'A'.random_int(100000, 999999),
            'email' => 'admin@test.ac.id',
            'password' => Hash::make('Secret#123'),
            'role' => 'admin',
        ]);
    }

    // --- Autentikasi ---

    public function test_guest_redirected_to_login(): void
    {
        $this->get('/mahasiswa/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_mahasiswa_login_redirects_to_dashboard(): void
    {
        $user = $this->makeMahasiswa();

        $this->post('/login', ['email' => $user->email, 'password' => 'Secret#123'])
            ->assertRedirect('/mahasiswa/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->makeAdmin();

        $this->post('/login', ['email' => $admin->email, 'password' => 'Secret#123'])
            ->assertRedirect('/admin/dashboard');
    }

    public function test_wrong_password_rejected(): void
    {
        $user = $this->makeMahasiswa();

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // --- RBAC ---

    public function test_mahasiswa_cannot_access_admin_routes(): void
    {
        $user = $this->makeMahasiswa();
        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/admin/export/csv')->assertForbidden();
    }

    public function test_admin_cannot_access_mahasiswa_routes(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin)->get('/mahasiswa/dashboard')->assertForbidden();
        $this->actingAs($admin)->post('/mahasiswa/aspirations', [])->assertForbidden();
    }

    // --- Pencegahan IDOR ---

    public function test_mahasiswa_sees_only_own_aspirations(): void
    {
        $owner = $this->makeMahasiswa('owner@test.ac.id');
        $intruder = $this->makeMahasiswa('intruder@test.ac.id');

        $owner->aspirations()->create([
            'title' => 'Laporan Milik Owner',
            'category' => 'Fasilitas',
            'description' => 'Deskripsi milik owner yang harus tersembunyi',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($intruder)
            ->get('/mahasiswa/dashboard')
            ->assertOk()
            ->assertDontSee('Laporan Milik Owner')
            ->assertDontSee('milik owner yang harus tersembunyi');
    }

    public function test_mahasiswa_cannot_delete_other_users_aspiration(): void
    {
        $owner = $this->makeMahasiswa('owner2@test.ac.id');
        $intruder = $this->makeMahasiswa('intruder2@test.ac.id');

        $victim = $owner->aspirations()->create([
            'title' => 'Laporan Korban IDOR',
            'category' => 'Akademik',
            'description' => 'Isi laporan yang harus tetap aman',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($intruder)
            ->delete('/mahasiswa/aspirations/'.$victim->id)
            ->assertNotFound();

        $this->assertDatabaseHas('aspirations', ['id' => $victim->id]);
    }

    public function test_mahasiswa_cannot_export_others_pdf_ticket(): void
    {
        $owner = $this->makeMahasiswa('owner3@test.ac.id');
        $intruder = $this->makeMahasiswa('intruder3@test.ac.id');

        $victim = $owner->aspirations()->create([
            'title' => 'Tiket Orang Lain',
            'category' => 'Layanan',
            'description' => 'Deskripsi tiket',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($intruder)
            ->get('/mahasiswa/aspirations/'.$victim->id.'/pdf')
            ->assertNotFound();
    }

    // --- Aturan hapus: hanya status Menunggu ---

    public function test_mahasiswa_can_delete_own_pending_aspiration(): void
    {
        $user = $this->makeMahasiswa();
        $item = $user->aspirations()->create([
            'title' => 'Laporan Bisa Dibatalkan',
            'category' => 'Fasilitas',
            'description' => 'Deskripsi laporan pending',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($user)
            ->delete('/mahasiswa/aspirations/'.$item->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('aspirations', ['id' => $item->id]);
    }

    public function test_mahasiswa_cannot_delete_processed_aspiration(): void
    {
        $user = $this->makeMahasiswa();
        $item = $user->aspirations()->create([
            'title' => 'Laporan Sedang Diproses',
            'category' => 'Akademik',
            'description' => 'Deskripsi laporan diproses',
            'status' => 'Diproses',
        ]);

        $this->actingAs($user)
            ->delete('/mahasiswa/aspirations/'.$item->id)
            ->assertRedirect();

        $this->assertDatabaseHas('aspirations', ['id' => $item->id]);
    }

    // --- XSS ---

    public function test_script_in_description_is_escaped(): void
    {
        $user = $this->makeMahasiswa();

        $this->actingAs($user)->post('/mahasiswa/aspirations', [
            'title' => 'Tes XSS pada judul',
            'category' => 'Fasilitas',
            'description' => 'Isi berbahaya <script>alert("xss")</script> di sini',
        ])->assertRedirect('/mahasiswa/dashboard');

        $response = $this->actingAs($user)->get('/mahasiswa/dashboard');
        $response->assertOk();

        // Tag script mentah tidak boleh muncul di HTML
        $response->assertDontSee('<script>alert("xss")</script>', false);
        // Teks terekam sebagai escaped entity (escape=false: cocokkan literal)
        $response->assertSee('&lt;script&gt;', false);
    }

    public function test_stored_xss_injection_attempt_is_escaped_on_admin_side(): void
    {
        $user = $this->makeMahasiswa();
        $admin = $this->makeAdmin();

        $user->aspirations()->create([
            'title' => 'Judul <img src=x onerror=alert(1)>',
            'category' => 'Layanan',
            'description' => '<svg/onload=alert(document.cookie)>',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertOk();
        $response->assertDontSee('<svg/onload=', false);
        $response->assertDontSee('<img src=x', false);
    }

    // --- Validasi input ---

    public function test_store_requires_valid_category(): void
    {
        $user = $this->makeMahasiswa();

        $this->actingAs($user)->post('/mahasiswa/aspirations', [
            'title' => 'Judul yang valid sekali',
            'category' => 'KategoriNgawur',
            'description' => 'Deskripsi yang cukup panjang untuk lolos validasi',
        ])->assertSessionHasErrors('category');
    }

    // --- Admin update status ---

    public function test_admin_can_update_status_and_response(): void
    {
        $user = $this->makeMahasiswa();
        $admin = $this->makeAdmin();

        $item = $user->aspirations()->create([
            'title' => 'Laporan Untuk Diproses',
            'category' => 'Fasilitas',
            'description' => 'Deskripsi laporan untuk admin update',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($admin)
            ->patch('/admin/aspirations/'.$item->id, [
                'status' => 'Diproses',
                'response' => 'Sedang kami tangani.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('aspirations', [
            'id' => $item->id,
            'status' => 'Diproses',
            'response' => 'Sedang kami tangani.',
        ]);
    }

    public function test_admin_rejects_invalid_status(): void
    {
        $user = $this->makeMahasiswa();
        $admin = $this->makeAdmin();

        $item = $user->aspirations()->create([
            'title' => 'Laporan Validasi Status',
            'category' => 'Akademik',
            'description' => 'Deskripsi untuk validasi status admin',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($admin)
            ->patch('/admin/aspirations/'.$item->id, ['status' => 'StatusNgawur'])
            ->assertSessionHasErrors('status');
    }

    // --- Export ---

    public function test_admin_export_csv_downloads_monthly_recap(): void
    {
        $user = $this->makeMahasiswa();
        $admin = $this->makeAdmin();

        $user->aspirations()->create([
            'title' => 'Data Export CSV',
            'category' => 'Layanan',
            'description' => 'Deskripsi data export',
            'status' => 'Selesai',
        ]);

        $this->actingAs($admin)
            ->get('/admin/export/csv')
            ->assertOk()
            ->assertDownload();
    }

    public function test_mahasiswa_cannot_access_export(): void
    {
        $user = $this->makeMahasiswa();

        $this->actingAs($user)->get('/admin/export/pdf')->assertForbidden();
    }

    // --- Export PDF ---

    public function test_mahasiswa_can_download_own_pdf_ticket(): void
    {
        $user = $this->makeMahasiswa();

        $item = $user->aspirations()->create([
            'title' => 'Tiket Milik Sendiri',
            'category' => 'Fasilitas',
            'description' => 'Deskripsi tiket sendiri untuk cetak PDF',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($user)->get('/mahasiswa/aspirations/'.$item->id.'/pdf');

        $response->assertOk();
        $this->assertStringContainsString('pdf', strtolower((string) $response->headers->get('Content-Type')));
        $this->assertStringStartsWith('%PDF', (string) $response->getContent());
    }

    public function test_admin_can_download_monthly_pdf(): void
    {
        $user = $this->makeMahasiswa();
        $admin = $this->makeAdmin();

        $user->aspirations()->create([
            'title' => 'Data Export PDF',
            'category' => 'Akademik',
            'description' => 'Deskripsi data export pdf bulanan',
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($admin)->get('/admin/export/pdf');

        $response->assertOk();
        $this->assertStringStartsWith('%PDF', (string) $response->getContent());
    }

    // --- Password hashing ---

    public function test_passwords_are_bcrypt_hashed(): void
    {
        $user = $this->makeMahasiswa();

        $this->assertTrue(str_starts_with($user->password, '$2y$') || str_starts_with($user->password, '$2y$12$'));
    }
}
