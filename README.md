# KursusKu

Project praktikum Pemrograman Web III — Platform edukasi kursus web interaktif bertahap menggunakan Laravel framework.

## Requirement
- PHP 8.3 atau lebih baru
- Composer 2.x
- Node.js (v20+ / v22+) & npm
- Laravel 11 / 12
- Laragon / web server lokal (Windows)
- Git

## Cara Menjalankan Project

1. **Buka terminal** pada direktori project:
   ```powershell
   cd C:\laragon\www\kursusku
   ```

2. **Pasang dependensi PHP & JavaScript**:
   ```powershell
   composer install
   npm install
   ```

3. **Konfigurasi file environment**:
   Salin `.env.example` menjadi `.env` jika belum tersedia:
   ```powershell
   copy .env.example .env
   ```

4. **Generate Application Key**:
   ```powershell
   php artisan key:generate
   ```

5. **Kompilasi asset Vite**:
   ```powershell
   npm run build
   ```
   *(Atau jalankan `npm run dev` untuk server asset lokal interaktif)*

6. **Jalankan development server Laravel**:
   ```powershell
   php artisan serve
   ```

7. **Akses aplikasi melalui browser**:
   Buka alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Riwayat Fitur Berdasarkan Milestone

### Milestone Week-07: Fondasi MVC, Route, Controller, & Blade
- **Named Route `home`**: Rute utama pada `routes/web.php` yang dipetakan ke controller.
- **`HomeController@index`**: Mempersiapkan data array `$siteName` dan `$featured` secara dinamis.
- **Blade Templating**: Rendering data menggunakan interpolasi aman `{{ }}` dan perulangan `@foreach`.
- **Pengamanan Evidence Lama**: Seluruh evidence praktikum Week-01 s.d. Week-06 tersalin aman dari prototype.

### Milestone Week-09: Blade Layout, Bootstrap via Vite, jQuery Ajax, & Formulir Pendaftaran
- **Desain Visual Merah-Hitam**:
  - Identitas warna tegas: Hitam (`#111111`), Merah (`#E50914`), Putih (`#FFFFFF`), Abu-abu (`#F5F5F5` dan `#6B6B6B`).
  - Desain realistis, profesional, dan bersih dari gaya AI generik (tanpa gradasi neon/ungu, glassmorphism, atau tombol kapsul berlebih).
- **Animasi Antarmuka Modern & Halus**:
  - Transisi slide-down navbar dengan indikator menu aktif beraksen merah.
  - Staggered fade-in & slide-up pada hero section.
  - Interaksi hover halus pada kartu kursus (*lift effect* dan border merah).
  - Fokus input/select yang responsif dan transisi alert dinamis.
- **Master Layout & Reusable Partial**:
  - `resources/views/layouts/app.blade.php`: Mengatur layout global dengan `@yield('content')`.
  - `resources/views/partials/navbar.blade.php`: Komponen navigasi reusable berbasis Bootstrap Navbar dengan responsive collapse.
- **Integrasi Bootstrap 5 & jQuery via Vite**:
  - `bootstrap`, `@popperjs/core`, dan `jquery` dipasang resmi melalui npm dan diproses melalui `resources/css/app.css` dan `resources/js/app.js`.
- **Formulir Pendaftaran Kursus (`enrollments/create.blade.php`)**:
  - Dilengkapi proteksi token CSRF (`@csrf`).
  - Validasi server-side pada `EnrollmentController@store` untuk validasi nama, format email, dan pilihan kursus dengan pesan kesalahan berbahasa Indonesia.
- **Ketersediaan Kursus Realtime (Ajax Availability)**:
  - Event listener jQuery `#course_id` mendeteksi pergantian pilihan kursus.
  - Mengirimkan request asynchronous ke endpoint JSON `GET /courses/{course}/availability` tanpa me-reload halaman.
  - Data sisa kursi diperbarui secara dinamis:
    - Web Dasar: Tersisa 12 kursi
    - PHP Dasar: Tersisa 8 kursi
    - Laravel Dasar: Tersisa 4 kursi

---

## Daftar Rute Aplikasi (`routes/web.php`)

| Method | URI | Named Route | Controller / Action |
|---|---|---|---|
| `GET` | `/` | `home` | `HomeController@index` |
| `GET` | `/courses/{course}/availability` | `courses.availability` | `CourseController@availability` |
| `GET` | `/enrollments/create` | `enrollments.create` | `EnrollmentController@create` |
| `POST` | `/enrollments` | `enrollments.store` | `EnrollmentController@store` |
| `GET` | `/pendaftaran` | `registration` | *Redirect ke `enrollments.create`* |

---

## Struktur Direktori Evidence

```text
evidence/
├── week-01/ s.d. week-06/  # Salinan aman dari prototype lama (30 berkas)
├── week-07/
│   ├── 01-tools.png
│   ├── 02-laravel-home.png
│   ├── 03-route-list.png
│   ├── 04-mobile-view.png
│   ├── 05-git-log.png
│   ├── request-lifecycle.pdf
│   ├── request-lifecycle.png
│   └── ai-usage-log.md
└── week-09/
    ├── 01-home.png          # Screenshot Beranda dengan Bootstrap Navbar
    ├── 02-form.png          # Screenshot Formulir Pendaftaran
    ├── 03-availability.png  # Screenshot Hasil Ajax Ketersediaan Kursus
    ├── 04-network-json.png  # Screenshot DevTools Network & Respon JSON
    ├── test-matrix.md       # Matriks 16 Skenario Pengujian Sistem
    └── ai-usage-log.md      # Catatan Penggunaan AI Assistant
```

---

## Belum Dikerjakan (Cakupan Milestone Selanjutnya)
- Relasi Database MySQL & Eloquent ORM (Pertemuan 10)
- CRUD Course Lengkap & Form Request Khusus (Pertemuan 11)
- Relasi Relasional Antar Model (Course, Participant, Enrollment) (Pertemuan 12)
- Fitur Pencarian Dinamis, Filter Status, dan Paginasi (Pertemuan 13)