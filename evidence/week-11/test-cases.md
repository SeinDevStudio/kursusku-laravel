# Test Cases Pertemuan 11 — Course CRUD II

Dokumentasi pengujian otomatis dan fungsional fitur CRUD Course lanjutan (Show, Form Request, Edit, Update, dan Delete) pada aplikasi KursusKu berdasarkan spesifikasi Panduan Praktikum Pertemuan 11.

---

## 1. Matriks Pengujian Fitur Course

| No | Skenario Pengujian | Target Endpoint/Method | Input/Kondisi | Hasil yang Diharapkan | Hasil Aktual | Status |
|:--:|-------------------|------------------------|---------------|-----------------------|--------------|:------:|
| 1 | Akses detail course | `GET /courses/{course}` | ID course valid di database (`SHOW-01`, fee Rp 250.000) | Status HTTP 200, view `courses.show` tampil dengan informasi lengkap kode, nama, biaya, kuota, tanggal mulai, dan status | Status 200, halaman detail tampil dengan seluruh atribut data kursus yang sesuai | PASS |
| 2 | Route model binding course tidak ditemukan / 404 | `GET /courses/{id}` | Request ke ID kursus yang tidak terdaftar di database (`/courses/99999`) | Status HTTP 404 Not Found dikembalikan secara otomatis oleh Route Model Binding | Status 404 Not Found dihasilkan tanpa error fatal aplikasi | PASS |
| 3 | Store course valid | `POST /courses` | Payload data lengkap & valid (`code: LAR-101`, `name: Laravel Masterclass`, `fee: 300000`, `quota: 30`, `status: open`) | Status 302 redirect ke `courses.index`, flash session success "Kursus berhasil ditambahkan.", record baru tercatat di database | Berhasil redirect dengan session success dan data baru tersimpan di tabel `courses` | PASS |
| 4 | Store course invalid | `POST /courses` | Payload dengan field wajib bernilai kosong (`code: ''`, `name: ''`) | Status 302 redirect back, session error pada key `code` dan `name` | Data ditolak oleh `StoreCourseRequest`, form menampilkan pesan validasi per field | PASS |
| 5 | Duplicate code saat store | `POST /courses` | Payload dengan `code` yang sudah terdaftar di database (`DUP-01`) | Status 302 redirect back, validation error pada `code` ("Kode kursus sudah digunakan.") | Data ditolak, sistem mengembalikan error validasi aturan unique code | PASS |
| 6 | Boundary quota 500 valid dan 501 invalid | `POST /courses` | Kasus A: `quota: 500` (batas maksimum valid)<br>Kasus B: `quota: 501` (melebihi batas aturan) | Kasus A berhasil tersimpan di DB; Kasus B ditolak dengan session error `quota` ("Kuota peserta maksimal 500 orang.") | Quota 500 tersimpan ke database, Quota 501 ditolak oleh validasi | PASS |
| 7 | Invalid start_date | `POST /courses` | Payload dengan format tanggal tidak valid (`start_date: 'bukan-format-tanggal-valid'`) | Status 302 redirect back, validation error pada key `start_date` ("Format tanggal mulai tidak valid.") | Data ditolak dan form menampilkan pesan kesalahan format tanggal | PASS |
| 8 | Invalid status | `POST /courses` & `PUT /courses/{id}` | Payload dengan nilai status di luar enum yang diizinkan (`status: 'archived'`) | Status 302 redirect back, validation error pada key `status` ("Status kursus harus berupa draft, open, atau closed.") | Data ditolak dan sistem menolak nilai status selain draft/open/closed | PASS |
| 9 | Akses halaman edit | `GET /courses/{course}/edit` | ID course valid di database (`EDIT-01`) | Status HTTP 200, view `courses.edit` tampil dengan formulir terisi data awal (pre-filled) dari course tersebut | Status 200, form edit berhasil menampilkan data existing secara lengkap | PASS |
| 10 | Update dengan code sendiri | `PUT /courses/{course}` | Mengubah nama, kuota, atau biaya dengan `code` tetap sama (`KEEP-01`) | Status 302 redirect ke `courses.index`, lolos validasi unique ignore self, data terupdate di database | Berhasil update data di database tanpa konflik unique code milik sendiri | PASS |
| 11 | Update menggunakan code milik course lain | `PUT /courses/{course}` | Mengubah `code` Course B menjadi sama persis dengan `code` Course A yang sudah ada | Status 302 redirect back, validation error pada key `code` ("Kode kursus sudah digunakan.") | Update ditolak dan kode Course B di database tetap tidak berubah | PASS |
| 12 | Delete course dummy | `DELETE /courses/{course}` | Request HTTP DELETE dengan CSRF token pada record course dummy (`DEL-01`) | Status 302 redirect ke `courses.index`, flash session success "Kursus berhasil dihapus.", record terhapus dari database | Berhasil redirect dengan flash success dan data terhapus dari tabel `courses` | PASS |

---

## 2. Rekap Hasil Automated Test

Perintah eksekusi:

```bash
php artisan test
```

Hasil aktual eksekusi:

```text
Tests:  28 passed (83 assertions)
Duration: 1.02s
```

Ringkasan metrik per test suite:
- **`CourseTest`**: **17 passed**, **60 assertions** (Mencakup pengujian lengkap CRUD Course: Index, Create, Store, Show, Edit, Update, Delete, serta validasi form Form Request).
- **`EnrollmentTest`**: **9 passed**, **21 assertions** (Seluruh fitur pendaftaran dan endpoint ketersediaan kuota Ajax tetap berjalan tanpa regresi).
- **`ExampleTest`**: **2 passed**, **2 assertions** (Pengujian dasar aksesibilitas root endpoint).
- **Failures**: **0**
- **Errors**: **0**

---

## 3. Kesimpulan

Seluruh fungsionalitas CRUD Course lanjutan (Pertemuan 11) telah teruji secara komprehensif melalui automated test suites maupun pengujian manual di browser:
1. Akses halaman detail (Show) dan formulir pengubahan data (Edit) bekerja sempurna dengan Route Model Binding.
2. Validasi form telah sepenuhnya dialihkan dari controller ke dedicated Form Request (`StoreCourseRequest` dan `UpdateCourseRequest`).
3. Aturan unique code pada saat update berhasil mengabaikan record milik sendiri menggunakan `Rule::unique()->ignore()`.
4. Proses update dan delete telah diproteksi dengan `@csrf` dan method spoofing (`@method('PUT')` serta `@method('DELETE')`).
5. Seluruh 8 data kursus utama di database MySQL tetap utuh dan aman.

