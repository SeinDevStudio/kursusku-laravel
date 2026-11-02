# KursusKu

Project praktikum Pemrograman Web III — Platform edukasi kursus web interaktif bertahap menggunakan Laravel framework.

## Requirement
- PHP 8.3 atau lebih baru
- Composer 2.x
- Node.js (v20+ / v22+) & npm
- Laravel 11 / 12
- Laragon / web server lokal (Windows) dengan MySQL aktif
- Git

## Cara Menjalankan Project

1. **Buka terminal** pada direktori project:
   ```powershell
   cd D:\laragon\www\kursusku
   ```

2. **Pasang dependensi PHP & JavaScript**:
   ```powershell
   composer install
   npm install
   ```

3. **Konfigurasi file environment**:
   Salin `.env.example` menjadi `.env` jika belum tersedia dan sesuaikan kredensial MySQL (`DB_DATABASE=kursusku`):
   ```powershell
   copy .env.example .env
   ```

4. **Generate Application Key**:
   ```powershell
   php artisan key:generate
   ```

5. **Jalankan Database Migration**:
   ```powershell
   php artisan migrate
   ```

6. **Kompilasi asset Vite**:
   ```powershell
   npm run build
   ```
   *(Atau jalankan `npm run dev` untuk server asset lokal interaktif)*

7. **Jalankan development server Laravel**:
   ```powershell
   php artisan serve
   ```

8. **Akses aplikasi melalui browser**:
   Buka alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000) atau via Laragon [http://kursusku.test](http://kursusku.test)

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

### Milestone Week-10: Laravel Database & CRUD I — Model, Migration, Tampil, Insert
- **Integrasi Database MySQL**: Menghubungkan aplikasi Laravel ke database MySQL `kursusku` menggunakan konfigurasi `.env`.
- **Migration Tabel `courses`**:
  - Migration terstruktur mendefinisikan kolom: `id`, `code` (string 20, unique), `name` (string 120), `description` (text, nullable), `fee` (unsignedInteger, default 0), `quota` (unsignedSmallInteger, default 20), `start_date` (date), `status` (enum: draft, open, closed, default draft), serta `created_at` dan `updated_at`.
- **Eloquent Model `Course`**:
  - Mengimplementasikan `App\Models\Course`.
  - Properti `$fillable` lengkap untuk mass-assignment data kursus.
  - Type casting terdefinisi: `fee` (integer), `quota` (integer), dan `start_date` (date).
- **Resource Route Course**:
  - Mendaftarkan endpoint CRUD melalui `Route::resource('courses', CourseController::class)`.
- **Daftar Kursus Database (`courses/index.blade.php`)**:
  - Menampilkan seluruh data kursus dari MySQL via `Course::latest()->get()`.
  - Desain tabel modern dengan card container, format mata uang Rupiah (`number_format`), tag kuota, dan status badge (*Dibuka*, *Draft*, *Ditutup*).
  - Tampilan empty state jika data belum tersedia.
- **Formulir Tambah Kursus (`courses/create.blade.php`)**:
  - Input field lengkap: Kode Kursus, Nama Kursus, Deskripsi, Biaya, Kuota Peserta, Tanggal Mulai, dan Status.
  - Penanganan error validasi per-field (`@error`, `.is-invalid`, `.invalid-feedback`) dan alert summary.
  - Retensi input lama menggunakan helper `old()`.
- **Penyimpanan Data Kursus Baru (`CourseController@store`)**:
  - Menyimpan record baru secara persisten ke database MySQL dengan `Course::create()`.
  - Flash message session sukses setelah penyimpanan berhasil.
- **Katalog Kursus Pilihan Dinamis di Beranda (`home.blade.php`)**:
  - Mengambil 3 kursus terbaru secara dinamis dari database (`Course::latest()->take(3)->get()`).
  - Pemetaan status manusiawi (*Tersedia*, *Draft*, *Ditutup*) dan text-clamping deskripsi agar tinggi kartu tetap sejajar dan konsisten.
- **Keamanan Data**:
  - Sebanyak 8 data kursus awal (`WEB001` s/d `LAR008`) tersimpan aman dan terawat di database MySQL.
- **Automated Testing**:
  - 8 skenario pengujian di `CourseTest` memverifikasi akses halaman index, form create, penyimpanan record valid, serta aturan validasi field.

#### Pengembangan Tambahan: Form Pendaftaran Terhubung Database
*(Catatan: Fitur ini merupakan pengembangan mandiri lanjutan di luar requirement resmi panduan Pertemuan 10)*
- **Migration Tabel `enrollments`**:
  - File `2026_11_02_202325_create_enrollments_table.php` mendefinisikan kolom: `id`, `name` (string), `email` (string), `course_id` (foreignId constrained ke `courses` cascade on delete), dan timestamps.
- **Model `Enrollment` & Relasi Eloquent**:
  - `App\Models\Enrollment` dengan `$fillable = ['name', 'email', 'course_id']`.
  - Relasi `belongsTo(Course::class)` pada `Enrollment`.
  - Relasi `hasMany(Enrollment::class)` pada `Course`.
- **Alur Penyimpanan Nyata (`EnrollmentController@store`)**:
  - Form `/pendaftaran` mengirimkan data ke `POST /enrollments`.
  - Controller memvalidasi input, me-resolve kode kursus (`code`) ke record database, mengambil `courses.id`, dan menyimpan record peserta via `Enrollment::create([...])`.
  - Redirect kembali ke formulir dengan flash success yang menyebutkan nama kursus yang berhasil didaftarkan.
- **Pengujian Persistensi**:
  - `EnrollmentTest` menguji persistensi fisik pendaftaran menggunakan `$this->assertDatabaseHas('enrollments', [...])`.

### Milestone Week-11: Laravel CRUD II — Form Request, Detail, Update, Delete
- **Arsitektur Form Request Terpisah**:
  - Memindahkan logika validasi dari controller ke Form Request resmi.
  - `App\Http\Requests\StoreCourseRequest`: Validasi penambahan kursus baru dengan authorization `true`, rules lengkap, dan pesan error berbahasa Indonesia.
  - `App\Http\Requests\UpdateCourseRequest`: Validasi pengubahan kursus dengan pengecualian unik kode milik record yang sedang diedit (`Rule::unique('courses', 'code')->ignore($this->route('course'))`).
- **Detail Kursus / Show (`courses/show.blade.php`)**:
  - Endpoint `GET /courses/{course}` ditangani oleh `CourseController@show(Course $course)` menggunakan **Route Model Binding**.
  - Menampilkan spesifikasi detail: kode, nama, deskripsi materi, biaya investasi belajar, kapasitas kuota, tanggal mulai pembelajaran, status publikasi, dan riwayat waktu sistem.
  - Menyediakan tombol navigasi Kembali, Edit, dan Hapus.
  - Penanganan otomatis HTTP 404 Not Found apabila ID kursus tidak ditemukan di database.
- **Edit & Update Kursus (`courses/edit.blade.php`)**:
  - Endpoint `GET /courses/{course}/edit` ditangani oleh `CourseController@edit(Course $course)`.
  - Form otomatis terisi nilai awal data existing menggunakan prioritas helper `old('field', $course->field)`.
  - Input tanggal mulai diformat khusus `Y-m-d` agar kompatibel dengan native HTML5 datepicker.
  - Form action mengarah ke `PUT /courses/{course}` dengan proteksi `@csrf` dan method spoofing `@method('PUT')`.
  - Controller memperbarui data via `$course->update($request->validated())` dan me-redirect ke index dengan flash success.
- **Hapus Kursus / Delete (`CourseController@destroy`)**:
  - Endpoint `DELETE /courses/{course}` dengan proteksi `@csrf` dan `@method('DELETE')`.
  - Dilengkapi dialog konfirmasi JavaScript (`onsubmit="return confirm(...);"`) sebelum eksekusi penghapusan.
  - Menghapus record via `$course->delete()` dan mengembalikan flash success.
- **Penyempurnaan Kolom Aksi Tabel Index**:
  - Menambahkan kolom `Aksi` pada tabel daftar kursus memuat tombol `Detail`, `Edit`, dan `Hapus`.
  - Mengimplementasikan class `.action-buttons-group` dengan ukuran dan padding yang proporsional sehingga seluruh tabel tampil utuh tanpa overflow horizontal pada viewport desktop normal.
- **Automated Testing Suite**:
  - Pengujian menyeluruh pada `tests/Feature/CourseTest.php` mencapai 17 skenario test (total project: 29 tests, 87 assertions, 0 failures, 0 errors).

---

## Daftar Rute Aplikasi (`routes/web.php`)

| Method      | URI                              | Named Route            | Controller / Action             | Deskripsi                                         |
| ----------- | -------------------------------- | ---------------------- | ------------------------------- | ------------------------------------------------- |
| `GET`       | `/`                              | `home`                 | `HomeController@index`          | Halaman Beranda & Kursus Pilihan Dinamis          |
| `GET`       | `/courses`                       | `courses.index`        | `CourseController@index`        | Menampilkan tabel daftar seluruh kursus           |
| `GET`       | `/courses/create`                | `courses.create`       | `CourseController@create`       | Formulir tambah kursus baru                       |
| `POST`      | `/courses`                       | `courses.store`        | `CourseController@store`        | Menyimpan data kursus baru (StoreCourseRequest)   |
| `GET`       | `/courses/{course}`              | `courses.show`         | `CourseController@show`         | Menampilkan halaman detail kursus (Model Binding) |
| `GET`       | `/courses/{course}/edit`         | `courses.edit`         | `CourseController@edit`         | Formulir edit kursus existing                     |
| `PUT/PATCH` | `/courses/{course}`              | `courses.update`       | `CourseController@update`       | Memperbarui data kursus (UpdateCourseRequest)     |
| `DELETE`    | `/courses/{course}`              | `courses.destroy`      | `CourseController@destroy`      | Menghapus record kursus dari database             |
| `GET`       | `/courses/{course}/availability` | `courses.availability` | `CourseController@availability` | Endpoint JSON ketersediaan kuota realtime (Ajax)  |
| `GET`       | `/enrollments/create`            | `enrollments.create`   | `EnrollmentController@create`   | Formulir pendaftaran peserta kursus               |
| `POST`      | `/enrollments`                   | `enrollments.store`    | `EnrollmentController@store`    | Memproses dan menyimpan pendaftaran ke MySQL      |
| `GET`       | `/pendaftaran`                   | `registration`         | *Closure*                       | Redirect alias ke `enrollments.create`            |

---

## Struktur Direktori Evidence

```text
evidence/
├── week-01/ s.d. week-06/       # Salinan aman dari prototype lama (30 berkas)
├── week-07/
│   ├── 01-tools.png
│   ├── 02-laravel-home.png
│   ├── 03-route-list.png
│   ├── 04-mobile-view.png
│   ├── 05-git-log.png
│   ├── request-lifecycle.pdf
│   ├── request-lifecycle.png
│   └── ai-usage-log.md
├── week-09/
│   ├── 01-home.png               # Screenshot Beranda dengan Bootstrap Navbar
│   ├── 02-form.png               # Screenshot Formulir Pendaftaran
│   ├── 03-availability.png       # Screenshot Hasil Ajax Ketersediaan Kursus
│   ├── 04-network-json.png       # Screenshot DevTools Network & Respon JSON
│   ├── test-matrix.md            # Matriks 16 Skenario Pengujian Sistem
│   └── ai-usage-log.md           # Catatan Penggunaan AI Assistant
├── week-10/
│   ├── 01-database.png           # Screenshot Database MySQL kursusku
│   ├── 02-courses-structure.png  # Screenshot Struktur Skema Tabel courses
│   ├── 03-create-form.png        # Screenshot Formulir Tambah Kursus Baru
│   ├── 04-index-8-data.png       # Screenshot Tabel Daftar Kursus (8 Record)
│   ├── 05-validation-error.png    # Screenshot Pesan Validasi Form Tambah Kursus
│   ├── 06-test-result.png        # Screenshot Hasil Automated Test Terminal
│   └── test-cases.md             # Dokumentasi 8 Skenario Uji CRUD Course I
└── week-11/
    ├── 01-course-index.png       # Screenshot Tabel Kursus dengan Kolom Aksi Utuh
    ├── 02-course-show.png        # Screenshot Halaman Detail Spesifikasi Kursus
    ├── 03-course-edit.png        # Screenshot Formulir Edit Kursus Pre-filled
    ├── 04-update-success.png     # Screenshot Notifikasi Sukses Pembaruan Kursus
    ├── 05-validation-error.png   # Screenshot Penolakan Validasi Form Edit
    ├── 06-delete-confirmation.png# Screenshot Dialog Konfirmasi Hapus Data
    ├── 07-delete-success.png     # Screenshot Notifikasi Sukses Penghapusan Kursus
    ├── 08-test-result.png        # Screenshot Hasil Automated Test Terminal
    └── test-cases.md             # Dokumentasi 12+ Skenario Uji CRUD Course II
```

---

## Belum Dikerjakan (Cakupan Milestone Selanjutnya)
- Relasi Relasional Antar Model (Course, Participant, Enrollment) (Pertemuan 12)
- Fitur Pencarian Dinamis, Filter Status, dan Paginasi (Pertemuan 13)
