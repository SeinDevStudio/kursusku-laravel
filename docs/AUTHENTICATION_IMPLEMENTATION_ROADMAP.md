# AUTHENTICATION & USER MANAGEMENT IMPLEMENTATION ROADMAP — KURSUSKU

**Project Directory:** `D:\laragon\www\kursusku`  
**Acuan:** [AUTHENTICATION_AUDIT.md](file:///D:/laragon/www/kursusku/docs/AUTHENTICATION_AUDIT.md)  
**Prioritas:** Keamanan > Stabilitas Project > Kompatibilitas Fitur Lama > Kualitas UI/UX

---

## Urutan Pelaksanaan & Catatan Arsitektur

Urutan fase mengikuti roadmap 10 tahap yang telah dirancang, dengan penekanan khusus pada **Phase 1 & 2** (menyiapkan `users` & middleware tanpa mengganggu tabel `courses`/`participants`/`enrollments`) serta **Phase 7 & 10** (menjaga kompatibilitas tombol CRUD pada halaman publik `/courses` dan memperbarui `CourseTest.php` agar tidak mengalami regresi saat middleware admin diaktifkan).

---

## PHASE 1 — Database & Authentication Foundation
- **Tujuan**: Menyiapkan struktur kolom `users` dan akun demo tanpa merusak data existing.
- **Perubahan Database**:
  - Buat migration baru: `database/migrations/xxxx_xx_xx_xxxxxx_add_role_and_profile_fields_to_users_table.php` untuk menambah kolom `username` (nullable, unique), `role` (`enum('admin','user')`, default `'user'`), `phone` (nullable), `profile_photo` (nullable), `is_active` (boolean, default `true`), dan `last_login_at` (timestamp, nullable).
  - Pertahankan tabel bawaan `password_reset_tokens` dan `sessions`.
- **Seeder**:
  - Buat `database/seeders/UserSeeder.php` menggunakan `firstOrCreate` / `updateOrCreate` untuk 1 akun `admin` (`admin@kursusku.test`) dan 3 akun `user` (`alya@kursusku.test`, `bima@kursusku.test`, `citra@kursusku.test`) dengan `Hash::make(...)`.
  - Panggil `UserSeeder` dari `database/seeders/DatabaseSeeder.php`.
- **Storage Link**: Jalankan `php artisan storage:link` agar `public/storage` terhubung ke `storage/app/public` untuk foto profil.
- **Dependencies**: Tidak perlu package tambahan.

---

## PHASE 2 — User Model, Password Hashing & Middleware
- **Tujuan**: Menyiapkan logika model, proteksi akun aktif, dan otorisasi role di backend.
- **File yang Dimodifikasi**:
  - `app/Models/User.php`: Tambahkan `username`, `phone`, `profile_photo` pada `#[Fillable(...)]` (**JANGAN** masukkan `role`, `is_active`, atau `last_login_at` ke fillable); tambahkan cast `'is_active' => 'boolean'`, `'last_login_at' => 'datetime'`; tambahkan helper `isAdmin(): bool` dan `isActive(): bool`.
  - `bootstrap/app.php`: Daftarkan alias middleware `active` dan `role`.
- **File yang Dibuat**:
  - `app/Http/Middleware/EnsureUserIsActive.php`: Memastikan akun nonaktif otomatis di-logout dan diarahkan ke `/login` dengan pesan error.
  - `app/Http/Middleware/EnsureUserHasRole.php`: Membatasi akses berdasarkan role (`403 Forbidden` jika tidak berhak).

---

## PHASE 3 — Login Page & Remember Me
- **Tujuan**: Mengimplementasikan proses autentikasi aman beserta halaman login beridentitas visual KursusKu.
- **File yang Dibuat**:
  - `app/Http/Controllers/Auth/LoginController.php` (`showLoginForm`, `login`, `logout`).
  - `app/Http/Requests/Auth/LoginRequest.php` (validasi email/username & password + `RateLimiter` maks. 5 percobaan/menit per kombinasi email & IP).
  - `resources/views/auth/login.blade.php` (desain split-screen asimetris Hitam-Merah-Putih, toggle show/hide password, checkbox `remember`, link Forgot Password).
- **File yang Dimodifikasi**:
  - `routes/web.php`: Tambah route `GET /login`, `POST /login` (middleware `guest`), dan `POST /logout` (middleware `auth`).
- **Persyaratan Keamanan**:
  - Wajib `Auth::attempt($credentials, $request->boolean('remember'))`.
  - Wajib cek `is_active` sebelum mempertahankan sesi.
  - Wajib `$request->session()->regenerate()` setelah login dan update `last_login_at`.
  - Wajib `Auth::logout()`, `$request->session()->invalidate()`, dan `$request->session()->regenerateToken()` saat logout.

---

## PHASE 4 — Forgot Password & Reset Password
- **Tujuan**: Menyediakan alur reset password nyata berbasis `Password` broker Laravel.
- **File yang Dibuat**:
  - `app/Http/Controllers/Auth/PasswordResetLinkController.php` (`create`, `store` via `Password::sendResetLink`).
  - `app/Http/Controllers/Auth/NewPasswordController.php` (`create`, `store` via `Password::reset` + `Hash::make` + regenerate remember token).
  - `resources/views/auth/forgot-password.blade.php`
  - `resources/views/auth/reset-password.blade.php`
- **File yang Dimodifikasi**:
  - `routes/web.php`: Tambah route `forgot-password` dan `reset-password` di dalam grup middleware `guest`.
- **Catatan Pengujian Lokal**: Gunakan `MAIL_MAILER=log` (`storage/logs/laravel.log`) atau Mailpit/Mailtrap pada Laragon untuk memverifikasi tautan reset password asli tanpa SMTP produksi.

---

## PHASE 5 — Personal Dashboard & Profile
- **Tujuan**: Halaman personal untuk setiap pengguna login (`USER` dan `ADMIN`).
- **File yang Dibuat**:
  - `app/Http/Controllers/ProfileController.php` (`dashboard`, `edit`, `update`, `editPassword`, `updatePassword`).
  - `app/Http/Requests/Profile/UpdateProfileRequest.php` (validasi `name`, `username` unique ignore current user, `email` unique ignore current user, `phone`, `profile_photo` image max 2MB).
  - `app/Http/Requests/Profile/UpdatePasswordRequest.php` (validasi `current_password` dengan rule `current_password`, `password` baru `confirmed` + `Password::defaults()`).
  - `resources/views/dashboard/index.blade.php` (foto profil, nama, email, username, role badge, tanggal daftar, last login, tombol Edit Profile, Change Password, Logout).
  - `resources/views/profile/edit.blade.php`
  - `resources/views/profile/password.blade.php`
- **File yang Dimodifikasi**:
  - `routes/web.php`: Grup middleware `['auth', 'active']` untuk `/dashboard`, `/profile`, `/profile/password`.
- **Persyaratan Keamanan**: Controller **hanya** menggunakan `$request->user()`, tidak menerima `user_id` maupun `role` dari request. Upload foto hanya ke disk `public` folder `avatars/` dan menghapus foto lama jika ada.

---

## PHASE 6 — Admin Dashboard & User Management
- **Tujuan**: Menyediakan panel administrasi khusus `ADMIN` untuk mengelola pengguna.
- **File yang Dibuat**:
  - `app/Http/Controllers/Admin/UserController.php` (`dashboard`, `index` dengan search/filter/pagination, `show`, `toggleStatus` untuk mengaktifkan/menonaktifkan akun dengan larangan menonaktifkan diri sendiri).
  - `resources/views/admin/dashboard.blade.php` (statistik total user, aktif, nonaktif, admin).
  - `resources/views/admin/users/index.blade.php`
  - `resources/views/admin/users/show.blade.php`
- **File yang Dimodifikasi**:
  - `routes/web.php`: Grup middleware `['auth', 'active', 'role:admin']` dengan prefix `admin` (`admin.dashboard`, `admin.users.index`, `admin.users.show`, `admin.users.toggle-status`).
  - Terapkan middleware `['auth', 'active', 'role:admin']` pada endpoint manajemen kursus (`courses.create`, `courses.store`, `courses.edit`, `courses.update`, `courses.destroy`) sambil mempertahankan `courses.index`, `courses.show`, dan `courses.availability` tetap publik.

---

## PHASE 7 — Navigation Integration
- **Tujuan**: Integrasi navigasi responsif berdasarkan status Guest, User, dan Admin tanpa merusak menu lama.
- **File yang Dimodifikasi**:
  - `resources/views/partials/navbar.blade.php`:
    - **Guest**: Tampilkan link publik (`Beranda`, `Daftar Kursus`, `Pendaftaran`) + tombol `Login`.
    - **User**: Tampilkan link publik + dropdown/menu `Dashboard`, `Profil`, `Logout` (POST form + `@csrf`).
    - **Admin**: Tampilkan link publik + `Admin Panel`, `Dashboard`, `Profil`, `Logout`.
  - `resources/views/courses/index.blade.php` & `resources/views/courses/show.blade.php`: Bungkus tombol `Tambah Kursus`, `Edit`, dan `Hapus` dengan `@if(auth()->user()?->isAdmin())`.
  - `resources/views/layouts/app.blade.php`: Sesuaikan link footer jika diperlukan agar konsisten.

---

## PHASE 8 — UI/UX Refinement & Responsive Design
- **Tujuan**: Memastikan seluruh halaman baru menyatu dengan design system Hitam-Merah-Putih KursusKu.
- **File yang Dimodifikasi**:
  - `resources/css/app.css`: Tambahkan styling khusus `.auth-split-wrapper`, `.auth-editorial-panel`, `.auth-form-panel`, `.dashboard-profile-card`, `.avatar-preview`, dan transisi fokus/hover yang halus tanpa gradasi/neon berlebihan.
  - `resources/js/app.js`: Tambahkan toggle visibilitas password dan preview gambar profil sebelum upload (ringan, vanilla/jQuery, tanpa mengganggu script `#course_id` availability).
- **Build Asset**: Jalankan `npm run build` setelah perubahan CSS/JS selesai.

---

## PHASE 9 — Security Testing
- **Tujuan**: Memverifikasi seluruh kontrol keamanan secara otomatis dan manual.
- **File Test yang Dibuat**:
  - `tests/Feature/AuthenticationTest.php`: Login valid, password salah, email tidak terdaftar, rate limiting brute-force, Remember Me, Forgot Password, Reset Password, Logout, dan penolakan akun nonaktif (`is_active = false`).
  - `tests/Feature/AuthorizationTest.php`: Guest akses `/dashboard` & `/admin`, `USER` akses `/admin` (`403`), `USER` akses CRUD write `/courses` (`403`), `ADMIN` akses `/admin` (`200`), percobaan privilege escalation (`role=admin` via form profil diabaikan).
  - `tests/Feature/ProfileTest.php`: Update nama/email/username/phone, validasi email/username duplikat, upload foto profil valid & penolakan file non-gambar, change password dengan cek `current_password`.

---

## PHASE 10 — Regression Testing & Final Verification
- **Tujuan**: Memastikan fitur Week-07 s.d. Week-13 tetap 100% berfungsi.
- **File yang Dimodifikasi**:
  - `tests/Feature/CourseTest.php`: Tambahkan `$this->actingAs($admin)` pada skenario test yang mengakses `create`, `store`, `edit`, `update`, `destroy` (sementara test publik `index`, `show`, search, filter, pagination tetap berjalan tanpa login).
- **Testing Checklist & Acceptance Criteria**:
  1. Seluruh route publik (`/`, `/courses`, `/courses/{course}`, `/courses/{course}/availability`, `/enrollments/create`, `/pendaftaran`) tetap dapat diakses oleh Guest tanpa error.
  2. Seluruh operasi write kursus dan halaman `/admin/*` hanya dapat diakses oleh `ADMIN`.
  3. Setiap user hanya dapat mengubah profil dan password miliknya sendiri.
  4. Seluruh PHPUnit test suite lulus (`0 failures`, `0 errors`).
  5. Data existing di database MySQL `kursusku` (8 courses, 4 participants, 4 enrollments) tetap utuh setelah `php artisan migrate` dan `php artisan db:seed --class=UserSeeder`.
