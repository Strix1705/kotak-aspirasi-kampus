# Portal Aspirasi & Pengaduan Kampus (AspirasiKampus.)

Aplikasi web berbasis Laravel 12 untuk memfasilitasi penyampaian aspirasi dan pengaduan mahasiswa kepada pihak kampus secara terstruktur, transparan, dan aman.

- **URL Aplikasi Live:** [https://kotakaspirasi.infinityfreeapp.com](https://kotakaspirasi.infinityfreeapp.com)
- **Identitas Pengembang:** Rizaldy Nur Hakim (20240810125) — Kelas TINFC-2024-04, Teknik Informatika, Universitas Kuningan

## Fitur Utama
- **Autentikasi & Manajemen Profil:** Registrasi mahasiswa, *login* multi-role, serta pembaruan nama dan kata sandi terenkripsi.
- **Role-Based Access Control (RBAC):** Pemisahan hak akses ketat antara Mahasiswa dan Admin menggunakan *middleware* khusus.
- **Manajemen Aspirasi (CRUD):** Mahasiswa dapat membuat, memantau status, dan membatalkan laporan; Admin dapat mengelola semua laporan, memberi tanggapan, dan mengekspor rekapitulasi.
- **Keamanan Aplikasi:** Perlindungan terhadap XSS, SQL Injection, CSRF, dan IDOR, serta transmisi terenkripsi menggunakan HTTPS (SSL/TLS).

## Teknologi yang Digunakan
- **Backend:** PHP 8.4, Laravel 12
- **Frontend:** Blade Template, Tailwind CSS
- **Database:** MySQL
- **Hosting & SSL:** InfinityFree

## Cara Instalasi Lokal
1. *Clone* repositori ini: `git clone https://github.com/Strix1705/kotak-aspirasi-kampus.git`
2. Masuk ke direktori proyek dan instal dependensi: `composer install && npm install`
3. Salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`
4. Sesuaikan kredensial database pada file `.env`, lalu jalankan migrasi & *seeder*: `php artisan migrate --seed`
5. Jalankan server lokal: `php artisan serve`

## Contoh Konfigurasi `.env`
```env
APP_NAME="AspirasiKampus"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=[https://kotakaspirasi.infinityfreeapp.com](https://kotakaspirasi.infinityfreeapp.com)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_aspirasi
DB_USERNAME=root
DB_PASSWORD=