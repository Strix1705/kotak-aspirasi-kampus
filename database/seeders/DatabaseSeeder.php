<?php

namespace Database\Seeders;

use App\Models\Aspiration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1 Akun Admin (password di-hash bcrypt) ---
        $admin = User::create([
            'name' => 'Administrator Kampus',
            'nim' => 'ADM001',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('AdminSecure#2026'),
            'role' => 'admin',
        ]);

        // --- 1 Akun Mahasiswa (password di-hash bcrypt) ---
        $mahasiswa = User::create([
            'name' => 'Budi Santoso',
            'nim' => '220101001',
            'email' => 'budi@student.kampus.ac.id',
            'password' => Hash::make('MahasiswaPass#2026'),
            'role' => 'mahasiswa',
        ]);

        // --- Contoh data aspirasi untuk demo dashboard ---
        $samples = [
            ['title' => 'AC Ruang Kelas C Lantai 3 Mati', 'category' => 'Fasilitas', 'status' => 'Menunggu', 'description' => 'AC di ruang kelas C lantai 3 sudah mati selama 2 minggu. Aktivitas belajar menjadi terganggu karena panas. Mohon segera diperbaiki.'],
            ['title' => 'Jadwal Perpustakaan Perlu Diperpanjang', 'category' => 'Layanan', 'status' => 'Diproses', 'description' => 'Perpustakaan tutup pukul 16.00, padahal banyak mahasiswa butuh waktu baca sore. Mohon diperpanjang hingga pukul 20.00.'],
            ['title' => 'Penjelasan Nilai UTS Mata Kuliah Algoritma', 'category' => 'Akademik', 'status' => 'Selesai', 'description' => 'Beberapa mahasiswa tidak menerima penjelasan nilai UTS. Mohon diberikan rincian penilaian agar transparan.', 'response' => 'Rincian nilai telah dibagikan melalui kelas, dosen bersedia konsultasi hari Jumat.'],
        ];

        foreach ($samples as $sample) {
            $mahasiswa->aspirations()->create($sample);
        }

        // Satu aspirasi milik admin? Tidak. Cukup 3 contoh milik mahasiswa.
        unset($admin);
    }
}
