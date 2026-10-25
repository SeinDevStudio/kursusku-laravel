# KursusKu

Project praktikum Pemrograman Web III (Milestone Week-07: Pengenalan Laravel, Route, Controller, dan Blade).

## Requirement
- PHP 8.3 atau lebih baru
- Composer
- Laravel 11 / 12
- Laragon / web server lokal (Windows)
- Git

## Cara Menjalankan
1. Buka terminal pada folder project (`C:\laragon\www\kursusku`).
2. Jalankan `composer install` (jika dependensi belum terpasang).
3. Salin `.env.example` menjadi `.env` jika file `.env` belum tersedia:
   ```powershell
   copy .env.example .env
   ```
4. Generate application key:
   ```powershell
   php artisan key:generate
   ```
5. Jalankan web server development Laravel:
   ```powershell
   php artisan serve
   ```
6. Buka alamat yang ditampilkan terminal pada web browser:
   `http://127.0.0.1:8000`

## Fitur Milestone 7
- **Named Route `home`**: Rute terpusat pada `routes/web.php` yang dipetakan ke controller.
- **`HomeController@index`**: Controller yang menangani alur request dan memisahkan data dengan tampilan.
- **Blade Landing Page (`home.blade.php`)**: Templating engine yang bersih dan dinamis memanfaatkan `@foreach` dan sintaks `{{ }}`.
- **Data Kursus dari Controller**: Data `$featured` didefinisikan pada controller dan diteruskan ke view menggunakan `compact()`.
- **Asset CSS & Image**: Penataan tata letak responsif melalui `public/css/style.css` dan integrasi gambar via helper `asset()`.

## Belum Dikerjakan (Out of Scope Week-07)
- Database (Model / Migration lanjutan)
- CRUD (Create, Read, Update, Delete)
- Authentication / Login / Register
- Relasi Database
- Ajax / Frontend SPA Framework