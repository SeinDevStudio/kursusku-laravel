# FULL PROJECT AUDIT — KURSUSKU
## Authentication, User Management, Personal Dashboard & UI/UX

**Project Directory:** `D:\laragon\www\kursusku`  
**Tanggal Audit:** 26 November 2026  
**Status Dokumen:** Audit Only (Read-Only — tidak ada perubahan source code maupun database)

---

## 1. Executive Summary

Project **KursusKu** adalah aplikasi web berbasis **Laravel 13.38.0** (PHP `8.3.33`) yang telah menyelesaikan modul **Week-07 s.d. Week-13**: fondasi MVC, layout Bootstrap 5 + Vite + jQuery, CRUD lengkap `Course` dengan Form Request, relasi multi-tabel `Participant`–`Enrollment`–`Course` dengan `ON DELETE CASCADE`, serta fitur Search, Filter Status, dan Pagination.

Dari sisi **Authentication, User Management, Personal Dashboard, dan Security Access Control**, sistem saat ini berstatus **NOT IMPLEMENTED**. Meskipun tabel bawaan Laravel (`users`, `password_reset_tokens`, `sessions`) dan model `App\Models\User` sudah tersedia dari instalasi awal, belum ada rute login, logout, lupa kata sandi, dashboard personal, manajemen pengguna, maupun middleware/policy otorisasi. Seluruh endpoint aplikasi—termasuk operasi destruktif `POST/PUT/DELETE` pada `/courses` dan laporan `/enrollments`—saat ini terbuka untuk publik (**CRITICAL WARNING**).

---

## 2. Project Inventory

| Kategori | Temuan Aktual | Lokasi / File Terkait | Status |
|---|---|---|---|
| **Framework & Runtime** | PHP `8.3.33`, Laravel Framework `13.38.0`, PHPUnit `12.5.12` | `composer.json` | `PASS` |
| **Frontend Stack** | Bootstrap `5.3.8`, `@popperjs/core` `^2.11.8`, jQuery `4.0.0`, Vite `8.0.0`, Tailwind CSS `^4.0.0` (dev) | `package.json`, `vite.config.js`, `resources/css/app.css`, `resources/js/app.js` | `PASS` |
| **Controllers** | `HomeController`, `CourseController`, `EnrollmentController`, `Controller` (base) | `app/Http/Controllers/` | `PASS` |
| **Form Requests** | `StoreCourseRequest`, `UpdateCourseRequest` | `app/Http/Requests/` | `PASS` |
| **Models** | `Course`, `Enrollment`, `Participant`, `User` | `app/Models/` | `WARNING` (`User` belum memiliki field/method role & profil) |
| **Middleware** | Konfigurasi standar Laravel 13 tanpa custom role/active middleware | `bootstrap/app.php` | `NOT IMPLEMENTED` |
| **Routes** | 16 routes aktif: `home`, `courses.*` (7 resource + `availability`), `enrollments.*` (3), `registration` (`/pendaftaran`) | `routes/web.php` | `WARNING` (semua terbuka publik) |
| **Blade Views** | `layouts/app`, `partials/navbar`, `home`, `courses/{index,create,show,edit}`, `enrollments/{create,index}` | `resources/views/` | `PASS` |
| **Assets & Storage** | `public/image/icon kursusku.png`, `public/images/hero-kursus.jpg`, `public/build/` | `public/`, `config/filesystems.php` | `WARNING` (`public/storage` symlink belum dibuat) |
| **Automated Tests** | 38 tests (120 assertions) lulus pada SQLite `:memory:` (`CourseTest`, `EnrollmentTest`, `ExampleTest`) | `tests/Feature/`, `phpunit.xml` | `PASS` |

---

## 3. Existing Feature Analysis

| Halaman / Fitur | Implementasi Sebenarnya | Status Data | Status |
|---|---|---|---|
| **Beranda (`/`)** | `HomeController@index` mengambil 3 kursus terbaru dari DB (`Course::latest()->take(3)->get()`). | Database dinamis | `PASS` |
| **Daftar Kursus (`/courses`)** | `CourseController@index` mendukung search `?q=` (`code`, `name`), filter `?status=`, `paginate(10)->withQueryString()`, empty state, dan Bootstrap 5 pagination. | Database dinamis | `PASS` |
| **Detail Kursus (`/courses/{course}`)** | `CourseController@show` menggunakan Route Model Binding. | Database dinamis | `PASS` |
| **CRUD Admin Kursus (`/courses/create`, `store`, `edit`, `update`, `destroy`)** | Menggunakan `StoreCourseRequest` & `UpdateCourseRequest`, proteksi CSRF & `@method('PUT'/'DELETE')`. **Belum dilindungi middleware auth/role.** | Database dinamis | `WARNING` |
| **Cek Kuota Ajax (`/courses/{course}/availability`)** | `CourseController@availability` mengecek DB berdasarkan `code`; memiliki fallback array statis `self::$courses` (`WEB-01`, `PHP-01`, `LAR-01`) jika kode belum ada di DB. | Hibrida DB + fallback statis | `PASS` |
| **Form Pendaftaran (`/enrollments/create`, `/pendaftaran`)** | `EnrollmentController@create` memuat dropdown `Participant` dan `Course` berstatus `open`. `store()` memvalidasi duplikasi `(participant_id, course_id)` dan menetapkan `status = 'registered'`. | Database dinamis | `PASS` |
| **Laporan Enrollment (`/enrollments`)** | `EnrollmentController@index` memakai `Enrollment::with(['participant', 'course'])->latest()->get()`. | Database dinamis | `PASS` |
| **Autentikasi, Profil, Admin User** | Belum ada route, controller, middleware, maupun view. | Belum tersedia | `NOT IMPLEMENTED` |

---

## 4. Database Analysis

- **Koneksi Aktif**: `mysql` (database `kursusku`), `SESSION_DRIVER=database`, `MAIL_MAILER=log`.
- **Kondisi Layanan Saat Audit**: MySQL (`127.0.0.1:3306`) dalam kondisi **tidak aktif** sehingga verifikasi struktur tabel langsung ke server MySQL tidak dapat dieksekusi (`NEEDS VERIFICATION` saat Laragon MySQL dinyalakan). Analisis skema di bawah berdasarkan seluruh file migration aktual di `database/migrations/`.
- **Tabel yang Sudah Tersedia**:
  1. `users` (`0001_01_01_000000_create_users_table.php`): `id`, `name`, `email` (unique), `email_verified_at` (nullable), `password`, `remember_token`, `timestamps`.
  2. `password_reset_tokens` (`0001_01_01_000000`): `email` (PK), `token`, `created_at`.
  3. `sessions` (`0001_01_01_000000`): `id` (PK), `user_id` (index), `ip_address`, `user_agent`, `payload`, `last_activity` (index).
  4. `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`.
  5. `courses`: `id`, `code` (unique, 20), `name` (120), `description` (nullable), `fee`, `quota`, `start_date`, `status` (enum `draft`, `open`, `closed`), `timestamps`.
  6. `participants`: `id`, `name` (100), `email` (unique, 120), `phone` (nullable, 20), `timestamps`.
  7. `enrollments`: `id`, `participant_id` (FK -> `participants.id` `ON DELETE CASCADE`), `course_id` (FK -> `courses.id` `ON DELETE CASCADE`), `status` (default `registered`), unique `(participant_id, course_id)`, `timestamps`.
- **Seeder**: `database/seeders/DatabaseSeeder.php` masih berisi template bawaan (`test@example.com`) dan belum memiliki seeder akun `ADMIN` & `USER`.
- **Potensi Konflik & Rekomendasi**:
  - Jangan membuat ulang tabel `users` atau `password_reset_tokens` dengan `migrate:fresh` (berisiko menghapus data `courses`, `participants`, dan `enrollments`). Gunakan **migration alter baru** (`add_profile_and_role_columns_to_users_table`).
  - `participants` dan `users` saat ini terpisah; relasi Week-12 tetap aman jika kolom baru hanya ditambahkan pada `users`.

---

## 5. Authentication Status

| Komponen | Kondisi Saat Ini | File Terkait | Status |
|---|---|---|---|
| **Login & Logout** | Belum ada controller, form, maupun route `/login` & `/logout`. | `routes/web.php` | `NOT IMPLEMENTED` |
| **Password Hashing** | Model `User` sudah memiliki cast `'password' => 'hashed'` (bcrypt/argon otomatis di Laravel 13). | `app/Models/User.php` | `PASS` (fondasi siap) |
| **Remember Me** | Kolom `remember_token` sudah ada di tabel `users`; belum ada form checkbox & logika `Auth::attempt($credentials, $remember)`. | `0001_01_01_000000_create_users_table.php` | `NOT IMPLEMENTED` |
| **Forgot & Reset Password** | Tabel `password_reset_tokens` dan `config/auth.php` broker `users` (`expire: 60`, `throttle: 60`) sudah siap. `MAIL_MAILER` saat ini `log` (bukan SMTP nyata). | `config/auth.php`, `config/mail.php` | `NOT IMPLEMENTED` |
| **Brute-force Protection** | Belum ada `RateLimiter` pada percobaan login. | - | `NOT IMPLEMENTED` |

**Rekomendasi Pengujian Reset Password Lokal:** Karena SMTP belum dikonfigurasi (`MAIL_MAILER=log`), pengujian lokal yang aman dan nyata adalah menggunakan **Mailpit / Mailtrap** via konfigurasi SMTP lokal, atau membaca tautan reset token resmi yang ditulis oleh `Password::sendResetLink()` ke `storage/logs/laravel.log` bila menggunakan driver `log`.

---

## 6. Navigation Mapping & Compatibility

| Halaman / Fitur | Route | Status Saat Ini | Rencana Akses | Risiko |
|---|---|---|---|---|
| Beranda | `GET /` (`home`) | `PUBLIC` | `PUBLIC` | Rendah |
| Katalog / Daftar Kursus | `GET /courses` (`courses.index`) | `PUBLIC` | `PUBLIC` (tombol CRUD hanya untuk `ADMIN`) | Sedang (tombol Edit/Hapus/Tambah di view harus di-guard `@if(auth()->user()?->isAdmin())`) |
| Detail Kursus | `GET /courses/{course}` (`courses.show`) | `PUBLIC` | `PUBLIC` (tombol Edit/Hapus hanya untuk `ADMIN`) | Sedang |
| Cek Kuota Ajax | `GET /courses/{course}/availability` (`courses.availability`) | `PUBLIC` | `PUBLIC` | Rendah (tidak boleh diblokir middleware auth agar form publik tetap jalan) |
| Tambah / Edit / Hapus Kursus | `courses.create`, `store`, `edit`, `update`, `destroy` | `PUBLIC` (**Bahaya**) | `ADMIN ONLY` (`auth`, `active`, `role:admin`) | Tinggi (berpotensi merusak test lama di `CourseTest.php` jika test tidak login sebagai admin) |
| Form Pendaftaran Kursus | `GET /enrollments/create`, `/pendaftaran`, `POST /enrollments` | `PUBLIC` | `PUBLIC` atau `AUTHENTICATED` (rekomendasi: tetap `PUBLIC` atau sesuaikan test `EnrollmentTest.php`) | Tinggi terhadap regresi `EnrollmentTest.php` jika dikunci tanpa update test |
| Laporan Enrollment | `GET /enrollments` (`enrollments.index`) | `PUBLIC` | `AUTHENTICATED` atau `ADMIN ONLY` | Sedang (perlu update `EnrollmentTest::test_submit_valid_enrollment` jika redirect ke `/enrollments` membutuhkan login) |
| Login / Forgot / Reset Password | `/login`, `/forgot-password`, `/reset-password/{token}` | Belum ada | `GUEST ONLY` (`guest` middleware) | Rendah |
| Logout | `POST /logout` | Belum ada | `AUTHENTICATED` (`auth` middleware) | Rendah |
| Personal Dashboard & Profil | `/dashboard`, `/profile`, `/profile/password` | Belum ada | `AUTHENTICATED` (`auth`, `active`) | Rendah |
| Admin Dashboard & User Mgmt | `/admin`, `/admin/users/*` | Belum ada | `ADMIN ONLY` (`auth`, `active`, `role:admin`) | Rendah |

---

## 7. Security Assessment

| Aspek Keamanan | Kondisi Aktual | Tingkat Risiko | Status |
|---|---|---|---|
| **Unauthorized Access & Privilege Escalation** | Seluruh route `/courses/*` (create/edit/delete) dan `/enrollments` tanpa middleware `auth` maupun `role`. | **KRITIS** | `FAIL` |
| **SQL Injection** | Controller menggunakan Eloquent ORM dan parameter binding (`CourseController`, `EnrollmentController`). | Rendah | `PASS` |
| **XSS** | Blade menggunakan `{{ }}` escaping secara konsisten di seluruh view. | Rendah | `PASS` |
| **CSRF** | Form `POST`, `PUT`, `DELETE` menggunakan `@csrf` dan `@method`. | Rendah | `PASS` |
| **Mass Assignment** | `Course`, `Participant`, `Enrollment`, `User` memakai `$fillable` / `#[Fillable]`. **Wajib:** jangan pernah memasukkan `role` atau `is_active` ke `$fillable` atau validasi form profil user! | Tinggi jika salah implementasi | `WARNING` |
| **Session Fixation & Hijacking** | `SESSION_DRIVER=database`. Saat implementasi login wajib `$request->session()->regenerate()`, saat logout wajib `invalidate()` + `regenerateToken()`. | Sedang | `NOT IMPLEMENTED` |
| **Brute-Force Login** | Belum ada `RateLimiter` (maks. 5 percobaan per menit per email+IP). | Tinggi | `NOT IMPLEMENTED` |
| **IDOR (Profile/User)** | Pada fitur profil, wajib selalu mengambil `auth()->user()` (bukan ID dari URL) agar user tidak bisa mengedit akun orang lain. | Tinggi jika pakai `{id}` | `NOT IMPLEMENTED` |
| **File Upload Vulnerability** | Untuk `profile_photo`: wajib validasi `image`, `mimes:jpg,jpeg,png,webp`, `max:2048`, simpan via `Storage::disk('public')->putFile('avatars', ...)` dengan nama acak, dan hapus foto lama secara aman. | Tinggi jika tidak divalidasi | `NOT IMPLEMENTED` |
| **Email Enumeration (Forgot Password)** | Gunakan pesan respons generik dari Laravel (`status` yang sama baik email ditemukan maupun tidak, atau sesuai standar `Password::sendResetLink`). | Sedang | `NOT IMPLEMENTED` |

---

## 8. UI/UX Assessment

- **Identitas Visual Aktual** (`resources/css/app.css`):
  - **Palet Warna**: Black (`#111111`, `#181818`, `#222222`), Red Accent (`#E50914`, hover `#C10811`), White (`#FFFFFF`), Gray Surface (`#F8F9FA`, `#E5E7EB`, `#6B7280`).
  - **Typography**: Font `'Inter'`, body `15px`, line-height `1.6`, heading tegas dengan `letter-spacing: -0.02em`.
  - **Komponen Konsisten**: `.btn-red`, `.btn-secondary-clean`, `.courses-table-card`, `.enrollment-card-wrapper` (split 2-kolom asimetris: panel gelap kiri + form putih kanan), `.alert-kursusku-success`, `.alert-kursusku-danger`.
- **Rekomendasi Desain Halaman Baru**:
  1. **Login Page**: Layout split-screen asimetris full-height (kiri: panel editorial `#111111` dengan tipografi besar, aksen garis/badge `#E50914`, narasi platform edukasi; kanan: area form putih bersih dengan fokus ring merah, toggle tampilkan password, checkbox Remember Me, link Forgot Password).
  2. **Forgot & Reset Password**: Mengadopsi layout split editorial yang sama dengan form ringkas dan feedback status pengiriman tautan yang jelas.
  3. **Personal Dashboard**: Hero compact `#111111` menampilkan sapaan personal, avatar bulat berbingkai, badge role (`USER`/`ADMIN`), email, username, tanggal bergabung, dan waktu *last login*; di bawahnya kartu ringkasan akun dan tombol cepat ke Edit Profile, Change Password, dan Logout.
  4. **Edit Profile & Change Password**: Kartu form dua kolom atau tabbed/stacked rapi dengan preview foto profil realtime (tanpa library berat), validasi inline Bootstrap `.is-invalid`.
  5. **Admin Dashboard & User Management**: Menggunakan desain `.courses-table-card` dan `.table-courses-clean` yang sudah matang (statistik ringkas user aktif/nonaktif/admin, tabel daftar user dengan search/filter/pagination, badge status aktif/nonaktif, tombol detail & toggle status).

---

## 9. Potential Breaking Changes & Risk Assessment

1. **Regresi `CourseTest.php` (Risiko TINGGI)**: 17 test CRUD Course di `tests/Feature/CourseTest.php` memanggil `/courses/create`, `POST /courses`, `PUT /courses/{id}`, dan `DELETE /courses/{id}` **tanpa login**. Jika rute tersebut langsung diproteksi middleware `auth` + `admin`, seluruh test write CRUD di `CourseTest.php` akan gagal (`302 Redirect` ke `/login`) kecuali `CourseTest.php` diperbarui menggunakan `$this->actingAs($admin)`.
2. **Regresi `EnrollmentTest.php` (Risiko TINGGI)**: `EnrollmentController@store` me-redirect ke `/enrollments`. Jika `/enrollments` dikunci hanya untuk user login, `EnrollmentTest::test_submit_valid_enrollment` akan menerima redirect ke login saat mengakses `/enrollments`.
3. **Symlink `public/storage` Belum Ada (Risiko SEDANG)**: Upload `profile_photo` ke disk `public` tidak akan tampil di browser sebelum menjalankan `php artisan storage:link`.
4. **MySQL Laragon Mati Saat Audit (Risiko SEDANG)**: Koneksi MySQL harus dipastikan aktif sebelum menjalankan migration alter `users` dan seeder demo.

---

## 10. Recommended Database Structure

Gunakan migration **baru** (`add_fields_to_users_table`) untuk menambahkan kolom berikut pada tabel `users` tanpa menyentuh `courses`, `participants`, atau `enrollments`:

- `username`: `string('username', 50)->nullable()->unique()->after('name')`
- `role`: `enum('role', ['admin', 'user'])->default('user')->after('password')`
- `phone`: `string('phone', 20)->nullable()->after('role')`
- `profile_photo`: `string('profile_photo')->nullable()->after('phone')`
- `is_active`: `boolean('is_active')->default(true)->after('profile_photo')`
- `last_login_at`: `timestamp('last_login_at')->nullable()->after('is_active')`

**Password Reset**: Tetap gunakan tabel bawaan `password_reset_tokens` (`email`, `token`, `created_at`) milik Laravel 13.

**Rekomendasi Akun Demo (untuk `UserSeeder` idempoten dengan `updateOrCreate` / `firstOrCreate`)**:
1. **Admin**: `admin@kursusku.test` | username: `admin` | role: `admin`
2. **User 1**: `alya@kursusku.test` | username: `alya` | role: `user`
3. **User 2**: `bima@kursusku.test` | username: `bima` | role: `user`
4. **User 3**: `citra@kursusku.test` | username: `citra` | role: `user`
*(Seluruh password wajib di-hash via `Hash::make(...)` dan seeder aman dijalankan berulang tanpa menghapus data lama).*

---

## 11. Recommended Authentication, Role & Testing Architecture

- **Auth Architecture**: Gunakan **Laravel native Session Guard (`web`)** dengan `AuthController` (`showLogin`, `login`, `logout`), `ForgotPasswordController` & `ResetPasswordController` berbasis `Illuminate\Support\Facades\Password`, `ProfileController` (dashboard, edit profile, update password), dan `Admin\UserController` (index, show, toggleActive). Tidak perlu menginstal Breeze/Jetstream karena project memiliki desain custom Merah-Hitam tersendiri.
- **Role & Permission Architecture**:
  - Method helper pada `App\Models\User`: `isAdmin(): bool` dan `isUser(): bool`.
  - Middleware `EnsureUserIsActive` (blokir & logout otomatis akun dengan `is_active = false` saat login maupun request berikutnya) dan `EnsureUserHasRole` (`role:admin`).
  - Gate / Policy (`UserPolicy`) untuk memastikan proteksi di backend (bukan sekadar menyembunyikan tombol di Blade).
- **Testing Strategy**: Tambahkan suite Feature Test terpisah (`AuthenticationTest.php`, `ProfileTest.php`, `AdminUserManagementTest.php`) serta perbarui `CourseTest.php` (dengan `actingAs($admin)` untuk aksi create/edit/delete) dan jalankan seluruh regression suite.
