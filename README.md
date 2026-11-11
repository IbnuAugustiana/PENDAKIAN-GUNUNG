# SIMAKSI Gunung

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)
![Midtrans](https://img.shields.io/badge/Midtrans-Sandbox-00AEEF?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Portfolio-success?style=for-the-badge)

## Deskripsi Singkat

SIMAKSI Gunung adalah aplikasi berbasis Laravel yang dirancang untuk membantu proses pengelolaan Surat Izin Masuk Kawasan Konservasi bagi kegiatan pendakian gunung. Sistem ini mendukung alur pendaftaran pendaki, manajemen data gunung dan rute pendakian, dashboard admin dan user, pembayaran Midtrans sandbox, export PDF, serta tampilan responsif untuk kebutuhan presentasi portfolio dan pengembangan lanjutan.

## Preview / Screenshot

![Landing Page] <img width="1685" height="862" alt="image" src="https://github.com/user-attachments/assets/613f5c97-4008-4e27-be59-bbc684f06690" />
![Dashboard User]<img width="1225" height="835" alt="image" src="https://github.com/user-attachments/assets/880caf58-d9ab-4277-a50a-68b167f36936" />

![Form Pendaftaran]<img width="1093" height="751" alt="image" src="https://github.com/user-attachments/assets/7afe2c2e-2b7f-444d-a9f3-12726cb675ac" />

![Riwayat Pendaftaran]<img width="1672" height="755" alt="image" src="https://github.com/user-attachments/assets/c9e1fd39-50d8-4548-8f38-ba25a87e6ddb" />

![Admin Dashboard]<img width="1896" height="872" alt="image" src="https://github.com/user-attachments/assets/fa0f8d4a-97c9-4b1f-8e55-4065b1183d77" />

![Admin Gunung]<img width="1895" height="862" alt="image" src="https://github.com/user-attachments/assets/7558a2be-f0d4-4691-8211-f640d078bf01" />

![Midtrans Payment] <img width="1898" height="861" alt="image" src="https://github.com/user-attachments/assets/3853e46b-80c2-4cb5-adcb-26615e146122" />


Jika file belum tersedia, lihat panduan di [docs/images/README.md](docs/images/README.md).

## Fitur Utama

- Landing page modern untuk pengenalan aplikasi.
- Authentication user dan admin.
- Dashboard user untuk melihat status dan riwayat pendaftaran.
- Dashboard admin untuk monitoring data inti aplikasi.
- Manajemen data gunung.
- Manajemen rute pendakian.
- Form pendaftaran SIMAKSI berbasis web.
- Upload dokumen identitas pendaki.
- Pembayaran menggunakan Midtrans sandbox.
- Export dokumen/riwayat dalam format PDF.
- Tampilan responsif untuk desktop dan mobile.

## Tech Stack

- Backend: Laravel 12
- Bahasa pemrograman: PHP 8.2+
- Database: MySQL
- Frontend: Blade, Bootstrap 5, JavaScript
- Build tool: Vite
- PDF export: `barryvdh/laravel-dompdf`
- Payment gateway: `midtrans/midtrans-php`
- Social login: Laravel Socialite

## Struktur Fitur User

- Registrasi dan login akun.
- Melihat landing page, panduan, daftar gunung, dan rute pendakian.
- Mengisi formulir pendaftaran SIMAKSI.
- Upload identitas/dokumen pendukung.
- Melihat ringkasan biaya dan status pembayaran.
- Melakukan pembayaran melalui Midtrans sandbox.
- Melihat riwayat pendaftaran.
- Mengunduh/cetak dokumen PDF ketika status sudah memenuhi syarat.

## Struktur Fitur Admin

- Login ke dashboard admin.
- Melihat ringkasan data utama aplikasi.
- Mengelola data gunung.
- Mengelola data rute pendakian.
- Meninjau data pendaftaran pengguna.
- Memantau status pembayaran dan status pendaftaran.
- Mengelola data pendukung yang tampil pada panel administrasi.

## Integrasi Pihak Ketiga

- Midtrans Sandbox untuk simulasi pembayaran.
- Laravel DOMPDF untuk export PDF.
- Google OAuth melalui Socialite.
- Google Maps API untuk dukungan lokasi/peta pada fitur tertentu.

Catatan:
Beberapa integrasi lanjutan masih bersifat pengembangan bertahap dan dirangkum pada bagian roadmap.

## Instalasi Lokal

1. Clone repository.
2. Masuk ke folder project.
3. Install dependency PHP:

```bash
composer install
```

4. Install dependency frontend:

```bash
npm install
```

5. Salin file environment:

```bash
cp .env.example .env
```

Untuk Windows PowerShell dapat menggunakan:

```powershell
Copy-Item .env.example .env
```

6. Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi `.env`

Sesuaikan nilai penting berikut pada file `.env`:

- `APP_NAME`, `APP_URL`, dan `APP_DEBUG`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MIDTRANS_MERCHANT_ID`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_SERVER_KEY`
- `GOOGLE_MAPS_KEY`
- `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
- `WEBHOOK_SECRET`, `INTEGRATION_TOKEN`
- `PEMINJAMAN_API_URL`, `SIMAKSI_WEBHOOK_URL`, `SIMAKSI_API_TOKEN`

Gunakan file `.env.example` sebagai template aman tanpa secret asli.

## Menjalankan Migration dan Seeder

Jalankan perintah berikut:

```bash
php artisan migrate --seed
```

Jika ingin menjalankan seeder tertentu secara manual:

```bash
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=UserSeeder
```

## Menjalankan Aplikasi

Jalankan backend Laravel:

```bash
php artisan serve
```

Jalankan Vite untuk asset frontend:

```bash
npm run dev
```

Setelah itu buka:

```text
http://127.0.0.1:8000
```

## Akun Demo Seeder

Beberapa akun demo ditemukan dari seeder yang ada di repository:

- Admin: `admin@simaksi.com` / `admin123`
- User: `user@simaksi.com` / `user123`
- Admin tambahan: `admin@gmail.com` / `admin123`

Catatan:
Gunakan akun demo ini hanya untuk pengujian lokal atau kebutuhan presentasi portfolio.

## Author

Muhamad Naufal Nazih  
Portfolio project
