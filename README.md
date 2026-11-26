# KursusKu

Project praktikum Pemrograman Web III — Platform edukasi kursus web interaktif bertahap menggunakan Laravel framework.

## Requirement
- PHP 8.3 atau lebih baru
- Composer 2.x
- Node.js (v20+ / v22+) & npm
- Laravel 13
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
  - *(Catatan Riwayat: Pada Week-09 formulir ini awalnya berupa prototipe frontend dengan mock Ajax. Struktur database pendaftaran kemudian dirintis pada Week-10 dan disempurnakan menjadi arsitektur relasional pada Week-12)*.
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
*(Catatan Riwayat: Fitur ini merupakan pengembangan mandiri lanjutan di luar requirement resmi panduan Pertemuan 10 sebelum dilakukannya normalisasi multi-tabel. Struktur penyimpanan langsung kolom 'name' dan 'email' pada tabel enrollments ini kemudian direfaktor secara relasional pada Milestone Week-12 menjadi entitas Participant dan Enrollment terpisah)*
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

### Milestone Week-12: Database Relationship & Enrollment

#### A. Database dan Migration
Arsitektur database KursusKu diperluas dari struktur single-entity menjadi relasi multi-tabel ternormalisasi melalui migration terencana:
- **Tabel `participants`**: Dibuat melalui migration `2026_11_15_224824_create_participants_table.php` untuk entitas peserta kursus, memuat kolom `id`, `name` (string 100), `email` (string 120, unique), `phone` (string 20, nullable), serta timestamps.
- **Penyesuaian Tabel `enrollments`**: Diperbarui melalui migration `2026_11_15_224834_update_enrollments_table_for_participants.php` menjadi tabel pivot/relasi yang menghubungkan peserta dan kursus dengan kolom `id`, `participant_id` (foreignId), `course_id` (foreignId), `status` (string 20, default `'registered'`), dan timestamps.
- **Migrasi Data Lama (Backfill)**: Migration menerapkan *two-phase data migration* yang aman. Data pendaftar lama dari tabel `enrollments` (`name: "Muhammad Husein"`, `email: "husein@example.com"`) otomatis dimigrasikan menjadi record peserta pada tabel `participants` (`id: 1`) sebelum kolom `name` dan `email` lama pada `enrollments` di-drop.
- **Integritas Referensial Foreign Key**:
  - `participant_id` mereferensikan `participants(id)` dengan aksi `ON DELETE CASCADE`.
  - `course_id` mereferensikan `courses(id)` dengan aksi `ON DELETE CASCADE`.
- **Composite Unique Constraint**: Menerapkan unique index gabungan `(participant_id, course_id)` pada tabel `enrollments` untuk mencegah peserta yang sama mendaftar lebih dari satu kali pada kursus yang sama.

#### B. Eloquent Model dan Relationship
Relasi database diimplementasikan secara terstruktur pada model Eloquent:
- **Model `Participant` (`app/Models/Participant.php`)**:
  - `$fillable`: `name`, `email`, `phone`.
  - `enrollments()`: Relasi `HasMany` ke `Enrollment::class` (Participant memiliki banyak Enrollment).
  - `courses()`: Relasi `BelongsToMany` ke `Course::class` melalui tabel `enrollments` dengan `withPivot('status')` dan `withTimestamps()` (Participant dapat memiliki banyak Course).
- **Model `Course` (`app/Models/Course.php`)**:
  - `enrollments()`: Relasi `HasMany` ke `Enrollment::class` (Course memiliki banyak Enrollment).
  - `participants()`: Relasi `BelongsToMany` ke `Participant::class` melalui tabel `enrollments` dengan `withPivot('status')` dan `withTimestamps()` (Course dapat memiliki banyak Participant).
- **Model `Enrollment` (`app/Models/Enrollment.php`)**:
  - `$fillable`: `participant_id`, `course_id`, `status`.
  - `participant()`: Relasi `BelongsTo` ke `Participant::class` (Enrollment dimiliki oleh Participant).
  - `course()`: Relasi `BelongsTo` ke `Course::class` (Enrollment dimiliki oleh Course).

#### C. Fitur Enrollment
- **Formulir Pendaftaran Relasional (`GET /enrollments/create`)**:
  - Dropdown Participant: Menampilkan nama dan email peserta terdaftar, mempertahankan nilai input via `old('participant_id')`.
  - Dropdown Course: Menampilkan kode dan nama kursus dengan filter status `open`, mempertahankan nilai input via `old('course_id')`.
  - Tombol submit bertuliskan *"Simpan Enrollment"*.
- **Validasi dan Penyimpanan Enrollment (`POST /enrollments`)**:
  - Validasi wajib dan keberadaan data (`exists`) untuk `participant_id` dan `course_id`.
  - Pencegahan duplikasi pendaftaran via `Rule::unique('enrollments')` dengan pesan kesalahan berbahasa Indonesia: *"Participant ini sudah terdaftar pada course tersebut."*.
  - Penetapan status pendaftaran secara otomatis oleh server sebagai `'registered'` (bukan input form pengguna).
- **Laporan Enrollment Relasional (`GET /enrollments`)**:
  - Menampilkan tabel laporan 4 kolom: Participant, Course, Start Date, dan Status.
  - Memanfaatkan eager loading `Enrollment::with(['participant', 'course'])->latest()->get()` untuk efisiensi kueri database.
  - Menyediakan tampilan *empty state* dengan pesan *"Belum ada enrollment."*.

#### D. Controller, Route, dan View
Komponen teknis yang diimplementasikan untuk modul enrollment:
- **Controller (`app/Http/Controllers/EnrollmentController.php`)**:
  - `create()`: Mengambil peserta terurut nama dan kursus berstatus `open`, lalu merender view `enrollments.create`.
  - `store()`: Memvalidasi relasi input dan composite unique, menetapkan status `'registered'`, menyimpan data via `Enrollment::create()`, dan redirect ke index dengan flash success.
  - `index()`: Mengambil data enrollment dengan eager loading relasi `participant` dan `course`, lalu merender view `enrollments.index`.
- **Route (`routes/web.php`)**:
  - Mendaftarkan resource route: `Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store'])`.
  - Mempertahankan rute alias `/pendaftaran` yang me-redirect ke `enrollments.create` demi kompatibilitas.
- **View**:
  - `resources/views/enrollments/create.blade.php`: Tampilan form pendaftaran dua dropdown.
  - `resources/views/enrollments/index.blade.php`: Tampilan tabel laporan enrollment.

#### E. Hasil Pengujian
- **Pengujian End-to-End (HTTP Kernel)**:
  - 8 dari 8 skenario pengujian E2E HTTP Kernel dinyatakan **PASS**:
    1. Akses form pendaftaran (`GET /enrollments/create`) menampilkan dropdown participant dan course (HTTP 200).
    2. Validasi penolakan submit participant kosong (HTTP 302).
    3. Validasi penolakan submit course kosong (HTTP 302).
    4. Pendaftaran valid peserta Alya ke Course 1 berhasil tersimpan dengan status `registered` (HTTP 302).
    5. Pendaftaran ulang peserta Alya ke Course 1 ditolak sebagai duplikat (HTTP 302).
    6. Pendaftaran peserta Alya ke Course 2 berhasil tersimpan dengan status `registered` (HTTP 302).
    7. Pendaftaran peserta Bima ke Course 1 berhasil tersimpan dengan status `registered` (HTTP 302).
    8. Akses halaman laporan (`GET /enrollments`) menampilkan data relasional peserta, kursus, tanggal mulai, dan badge status `registered` (HTTP 200).
- **Pengujian Cascade Delete**:
  - **Cascade Delete Test A (Course)**: **PASS** — Penghapusan record course dummy secara otomatis menghapus record enrollment terkait via foreign key MySQL, sementara data participant tetap aman.
  - **Cascade Delete Test B (Participant)**: **PASS** — Penghapusan record participant dummy secara otomatis menghapus record enrollment terkait via foreign key MySQL, sementara data course tetap aman.

#### F. Kondisi Data Akhir
- **Courses**: 8 record kursus awal (`WEB001` s/d `LAR008`) tetap utuh.
- **Participants**: 4 record peserta (1 peserta awal Muhammad Husein + 3 peserta pengujian: Alya Rahma, Bima Putra, Citra Nabila).
- **Enrollments**: 4 record pendaftaran aktif berstatus `registered` (1 pendaftaran awal Muhammad Husein + 3 pendaftaran hasil pengujian E2E).
- **Pembersihan Data**: Seluruh data dummy yang dibuat khusus untuk pengujian cascade delete telah dibersihkan secara tuntas dari database MySQL.

### Milestone Week-13: Searching, Filter Status, dan Pagination

#### A. Search Course
- Pencarian pada halaman daftar kursus menggunakan parameter GET `q`.
- Pencarian mencocokkan kata kunci pada kolom `code` dan `name` menggunakan Eloquent Query Builder (`where` / `orWhere` dengan `like`).

#### B. Filter Status
- Filter daftar kursus berdasarkan status `open`, `closed`, dan `draft`.
- Parameter `q` dan `status` dapat digunakan secara bersamaan maupun terpisah.

#### C. Pagination
- Daftar kursus dipaginasi sebanyak 10 data per halaman menggunakan `paginate(10)`.
- Menggunakan `withQueryString()` agar parameter `q` dan `status` tetap dipertahankan saat berpindah halaman.
- Menggunakan tampilan pagination Bootstrap 5 melalui `Paginator::useBootstrapFive()` pada `AppServiceProvider::boot()`.

#### D. Tampilan Course Index (`resources/views/courses/index.blade.php`)
- Form `GET` menuju `route('courses.index')` berisi input pencarian `q`, dropdown filter `status`, tombol **Cari**, dan tombol **Reset**.
- Badge jumlah menampilkan total seluruh hasil pencarian/filter menggunakan `$courses->total()`.
- Penomoran baris tabel mengikuti posisi halaman pagination menggunakan `($courses->firstItem() ?? 0) + $loop->index`.
- Menampilkan empty state khusus (*"Kursus Tidak Ditemukan"*) beserta tombol **Hapus Pencarian & Filter** ketika hasil pencarian atau filter kosong, serta pagination links `$courses->links()`.
- Seluruh fitur pencarian, filter, dan pagination bekerja melalui request GET standar tanpa bergantung pada JavaScript.

#### E. Automated Testing
- Penambahan 9 pengujian Feature baru pada `tests/Feature/CourseTest.php` (pencarian berdasarkan kode, pencarian berdasarkan nama, filter `open`/`closed`/`draft`, kombinasi search dan filter, pagination 10 data per halaman, retensi query string `withQueryString()`, dan empty state).
- Penyesuaian 3 pengujian lama pada `tests/Feature/EnrollmentTest.php` agar sesuai dengan skema Week-12 (`participant_id` dan `course_id` berupa ID).
- **Hasil PHPUnit** (dijalankan dengan konfigurasi `phpunit.xml` menggunakan SQLite in-memory, bukan pengujian langsung ke MySQL melalui browser):
  - **Total:** 38 tests (120 assertions)
  - **PASS:** 38
  - **FAIL:** 0

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
| `GET`       | `/enrollments`                   | `enrollments.index`    | `EnrollmentController@index`    | Menampilkan tabel laporan enrollment relasional   |
| `GET`       | `/enrollments/create`            | `enrollments.create`   | `EnrollmentController@create`   | Formulir pendaftaran relasional peserta ke kursus |
| `POST`      | `/enrollments`                   | `enrollments.store`    | `EnrollmentController@store`    | Memvalidasi dan menyimpan enrollment ke MySQL     |
| `GET`       | `/pendaftaran`                   | `registration`         | *Closure*                       | Redirect alias lama ke `enrollments.create`       |

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
│   └── request-lifecycle.png
├── week-09/
│   ├── 01-home.png               # Screenshot Beranda dengan Bootstrap Navbar
│   ├── 02-form.png               # Screenshot Formulir Pendaftaran
│   ├── 03-availability.png       # Screenshot Hasil Ajax Ketersediaan Kursus
│   ├── 04-network-json.png       # Screenshot DevTools Network & Respon JSON
│   └── test-matrix.md            # Matriks 16 Skenario Pengujian Sistem
├── week-10/
│   ├── 01-database.png           # Screenshot Database MySQL kursusku
│   ├── 02-courses-structure.png  # Screenshot Struktur Skema Tabel courses
│   ├── 03-create-form.png        # Screenshot Formulir Tambah Kursus Baru
│   ├── 04-index-8-data.png       # Screenshot Tabel Daftar Kursus (8 Record)
│   ├── 05-validation-error.png    # Screenshot Pesan Validasi Form Tambah Kursus
│   ├── 06-test-result.png        # Screenshot Hasil Automated Test Terminal
│   └── test-cases.md             # Dokumentasi 8 Skenario Uji CRUD Course I
├── week-11/
│   ├── 01-course-index.png       # Screenshot Tabel Kursus dengan Kolom Aksi Utuh
│   ├── 02-course-show.png        # Screenshot Halaman Detail Spesifikasi Kursus
│   ├── 03-course-edit.png        # Screenshot Formulir Edit Kursus Pre-filled
│   ├── 04-update-success.png     # Screenshot Notifikasi Sukses Pembaruan Kursus
│   ├── 05-validation-error.png   # Screenshot Penolakan Validasi Form Edit
│   ├── 06-delete-confirmation.png# Screenshot Dialog Konfirmasi Hapus Data
│   ├── 07-delete-success.png     # Screenshot Notifikasi Sukses Penghapusan Kursus
│   ├── 08-test-result.png        # Screenshot Hasil Automated Test Terminal
│   └── test-cases.md             # Dokumentasi 12+ Skenario Uji CRUD Course II
├── week-12/
│   ├── 01-erd-final.png          # Screenshot ERD Skema Relasional
│   ├── 02-screenshot-migration.png # Screenshot Status Migrasi Database
│   ├── 03-foreign-key-database.png # Screenshot DDL Constraint Foreign Key
│   ├── 04-source-migration-1.png # Screenshot Source Migration Participants
│   ├── 04-source-migration-2.png # Screenshot Source Migration Enrollments
│   ├── 05-model-course.png       # Screenshot Relasi Model Course
│   ├── 05-model-enrollment.png   # Screenshot Relasi Model Enrollment
│   ├── 05-model-participant.png  # Screenshot Relasi Model Participant
│   ├── 06-controller-create.png  # Screenshot Controller Method create()
│   ├── 06-controller-index.png   # Screenshot Controller Method index()
│   ├── 06-controller-store.png   # Screenshot Controller Method store()
│   ├── 07-form-enrollment.png    # Screenshot Formulir Pendaftaran Dropdown
│   ├── 08-duplicate-message.png  # Screenshot Notifikasi Penolakan Duplikasi
│   ├── 09-laporan-enrollment.png # Screenshot Tabel Laporan Enrollment
│   ├── 10-cascade-delete-course.png.png # Screenshot Uji Cascade Delete Course
│   ├── 11-cascade-delete-participant.png.png # Screenshot Uji Cascade Delete Participant
│   └── test-case.md              # Dokumentasi Kasus Uji Cascade Delete
└── week-13/
    ├── 01-search-empty.png
    ├── 02-search-single-result.png
    ├── 03-pagination.png
    ├── 04-search-without-js.png
    └── 05-filter-pagination-page-2.png
```

---

## Sistem Autentikasi, Dashboard Personal & Manajemen Pengguna

### Struktur Autentikasi & Otorisasi
- **Guard & Session**: Laravel native session guard (`web`) dengan penyimpanan sesi di database (`sessions`), regenerasi sesi saat login, invalidasi sesi + regenerasi token CSRF saat logout, serta proteksi *rate limiting* (maksimal 5 percobaan gagal per menit).
- **Role & Middleware**:
  - `active` (`App\Http\Middleware\EnsureUserIsActive`): Memastikan akun berstatus aktif (`is_active = true`).
  - `role:admin` (`App\Http\Middleware\EnsureUserHasRole`): Membatasi akses halaman administrasi (`/admin/*`) dan operasi tulis manajemen kursus (`create`, `store`, `edit`, `update`, `destroy`) hanya untuk `ADMIN`.
  - Halaman publik (`/`, `/courses`, `/courses/{course}`, `/courses/{course}/availability`, `/enrollments/create`, `/pendaftaran`) tetap dapat diakses tanpa login.
- **Dashboard & Profil**:
  - `/dashboard`: Dashboard personal dengan ringkasan akun, tanggal bergabung, login terakhir, dan pintasan pengaturan.
  - `/profile` & `/profile/password`: Edit nama, username, email, telepon, unggah foto profil (disk `public` via `php artisan storage:link`), dan ubah kata sandi dengan verifikasi kata sandi lama.
- **Admin Panel (`/admin`, `/admin/users`)**:
  - Statistik pengguna aktual dari database, pencarian, filter role/status, pagination, tambah/edit pengguna, ubah role, dan aktivasi/nonaktivasi akun.

### Konfigurasi Email Reset Password
- Fitur *Forgot Password* (`/forgot-password`) dan *Reset Password* (`/reset-password/{token}`) menggunakan **Laravel Password Broker** (`password_reset_tokens`).
- Konfigurasi default lokal menggunakan `MAIL_MAILER=log`. Saat pengujian lokal, tautan reset password asli dapat dilihat pada file `storage/logs/laravel.log`. Untuk pengiriman email nyata, ubah `MAIL_MAILER=smtp` beserta kredensial SMTP lokal (seperti Mailpit/Mailtrap) pada `.env`.

### Akun Demo & Menjalankan Seeder
Seeder `Database\Seeders\UserSeeder` bersifat idempoten (aman dijalankan ulang tanpa menduplikasi akun atau menimpa kata sandi akun yang sudah ada) dan menghasilkan password acak yang kuat saat pembuatan awal:
- **1 ADMIN**: `admin@kursusku.test` (username: `admin`)
- **3 USER**: `alya@kursusku.test` (`alya`), `bima@kursusku.test` (`bima`), `citra@kursusku.test` (`citra`)

Perintah menjalankan seeder dan storage link:
```powershell
php artisan db:seed --class=UserSeeder
php artisan storage:link
```
*(Kredensial awal dicetak pada output terminal saat akun baru dibuat oleh seeder).*

### Menjalankan Automated Tests
```powershell
php vendor/bin/phpunit
```
