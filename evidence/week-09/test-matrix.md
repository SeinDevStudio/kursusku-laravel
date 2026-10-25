# Test Matrix - Week-09 (Blade Layout, Bootstrap, jQuery Ajax, Form)

Berikut adalah matriks pengujian fungsional dan antarmuka untuk Milestone Week-09 pada aplikasi KursusKu.

| No | Skenario Pengujian | Input / Tindakan | Hasil yang Diharapkan | Hasil Aktual | Status |
|:--:|---|---|---|---|:--:|
| 1 | Akses Beranda | Buka `GET /` | Halaman landing page KursusKu tampil dengan tema Red-Black dan asset Vite | HTTP 200 OK, teks dan kartu kursus tampil sempurna | **PASS** |
| 2 | Navbar Bootstrap | Buka desktop browser | Navbar hitam `#111111` dengan logo KursusKu, link Beranda (aktif merah), dan Pendaftaran | Navbar tampil rapi dan link navigasi berfungsi | **PASS** |
| 3 | Mobile Navbar Collapse | Kecilkan viewport (< 768px), klik hamburger | Menu navbar collapse terbuka/tertutup secara mulus via Bootstrap JS | Transisi collapse bekerja tanpa glitch | **PASS** |
| 4 | Akses Halaman Pendaftaran | Buka `GET /enrollments/create` atau klik Pendaftaran | Formulir pendaftaran terbuka menggunakan layout master yang sama | HTTP 200 OK, form card putih rapi tampil | **PASS** |
| 5 | Route Alias Pendaftaran | Buka `GET /pendaftaran` | Otomatis di-redirect ke `enrollments.create` | HTTP 302 Redirect ke `/enrollments/create` | **PASS** |
| 6 | Ketersediaan Kursus Web Dasar | Pilih "WEB-01 — Web Dasar" | Ajax GET ke `/courses/WEB-01/availability` menghasilkan 12 kursi | Respon JSON `{"remaining": 12}`, box menampilkan "Tersisa 12 kursi" | **PASS** |
| 7 | Ketersediaan Kursus PHP Dasar | Pilih "PHP-01 — PHP Dasar" | Ajax GET ke `/courses/PHP-01/availability` menghasilkan 8 kursi | Respon JSON `{"remaining": 8}`, box menampilkan "Tersisa 8 kursi" | **PASS** |
| 8 | Ketersediaan Kursus Laravel Dasar | Pilih "LAR-01 — Laravel Dasar" | Ajax GET ke `/courses/LAR-01/availability` menghasilkan 4 kursi | Respon JSON `{"remaining": 4}`, box menampilkan "Tersisa 4 kursi" | **PASS** |
| 9 | Submit Form Valid | Nama: "Budi", Email: "budi@mail.com", Kursus: "WEB-01" | Form lolos validasi, tersimpan, redirect dengan pesan sukses | Redirect kembali dengan alert sukses hijau | **PASS** |
| 10 | Validasi Email Invalid | Input email: "bukan-email" | Validasi gagal, muncul pesan "Format email tidak valid." | Muncul feedback invalid Bootstrap di bawah field email | **PASS** |
| 11 | Validasi Nama Invalid | Input nama: "Al" (< 3 karakter) | Validasi gagal, muncul pesan "Nama lengkap minimal 3 karakter." | Muncul feedback invalid Bootstrap di bawah field nama | **PASS** |
| 12 | State Transisi Ajax | Ubah pilihan dropdown kursus | Muncul status loading "Memeriksa ketersediaan..." lalu berubah hijau (*alert-success*) | Transisi fadeOut/fadeIn berjalan halus | **PASS** |
| 13 | Inspeksi DevTools Network | Periksa tab Network saat ganti kursus | Request tercatat sebagai asynchronous (Fetch/XHR) status 200 | Request `availability` tercatat dengan Payload JSON | **PASS** |
| 14 | Verifikasi Console Browser | Buka tab Console pada Developer Tools | Tidak ada error JavaScript runtime atau 404 resource | Console bersih (0 errors) | **PASS** |
| 15 | Build Asset Vite | Jalankan `npm run build` | Asset CSS dan JS terkompilasi ke `public/build` tanpa error | Build selesai dalam < 300ms tanpa error | **PASS** |
| 16 | Named Route Laravel | Jalankan `php artisan route:list` | Semua route (`home`, `courses.availability`, `enrollments.*`) terdaftar | Seluruh 5 named route terdaftar dengan controller yang sesuai | **PASS** |

### Kesimpulan Pengujian
Semua 16 skenario pengujian berstatus **PASS** dan telah diverifikasi secara aktual pada server Laravel lokal.