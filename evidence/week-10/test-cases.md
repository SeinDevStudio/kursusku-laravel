# Test Cases Pertemuan 10 — Course CRUD

Pengujian dilakukan untuk memastikan fungsionalitas CRUD Course (Create + Read), integritas skema database, serta aturan validasi form pada aplikasi KursusKu berjalan sesuai dengan spesifikasi Panduan Praktikum Pertemuan 10.

| No | Skenario Pengujian | Input/Kondisi | Hasil yang Diharapkan | Hasil Aktual | Status |
|---:|-------------------|---------------|-----------------------|--------------|:------:|
| 1 | Membuka halaman daftar kursus (`GET /courses`) | Database memiliki data kursus valid (`TEST-01`, fee Rp 150.000) | Status HTTP 200, menampilkan judul "Daftar Kursus", kode, nama, dan biaya terformat | Status 200, seluruh data kursus ditampilkan dengan benar pada tabel | PASS |
| 2 | Membuka formulir tambah kursus (`GET /courses/create`) | Request HTTP GET ke route form create | Status HTTP 200, menampilkan heading "Tambah Kursus Baru" dan field input lengkap (`code`, `name`, `quota`, `fee`) | Status 200, struktur formulir dan atribut input lengkap | PASS |
| 3 | Menyimpan kursus baru dengan data valid (`POST /courses`) | Payload lengkap: `code: LAR-101`, `name: Laravel Masterclass`, `fee: 300000`, `quota: 30`, `status: open` | Redirect ke `/courses`, flash session success "Kursus berhasil ditambahkan.", data tersimpan di tabel `courses` | Berhasil redirect dengan flash message dan data tercatat di database MySQL | PASS |
| 4 | Validasi penolakan kode kursus kosong | Payload POST dengan field `code` kosong (`''`) | Validasi gagal, sistem mengembalikan error pada field `code` | Data ditolak, session memiliki pesan kesalahan validasi `code` | PASS |
| 5 | Validasi penolakan kode kursus > 20 karakter | Payload POST dengan `code` melebihi 20 karakter | Validasi gagal, sistem mengembalikan error pada field `code` | Data ditolak, pesan error `code` melebihi panjang maksimal ditampilkan | PASS |
| 6 | Validasi penolakan nama kursus > 120 karakter | Payload POST dengan `name` berisi 121 karakter (`str_repeat('A', 121)`) | Validasi gagal, sistem mengembalikan error pada field `name` | Data ditolak, pesan error `name` melebihi batas maksimal ditampilkan | PASS |
| 7 | Validasi penolakan kuota kursus tidak valid | Payload POST dengan `quota: 0` atau `quota: 501` (di luar batas 1–500) | Validasi gagal untuk kedua nilai kuota ekstrem | Kedua input kuota ditolak, error validasi field `quota` ditampilkan | PASS |
| 8 | Validasi penolakan status kursus tidak valid | Payload POST dengan nilai status di luar enum (`status: 'archived'`) | Validasi gagal, status harus bernilai salah satu dari: `draft`, `open`, atau `closed` | Data ditolak, sistem mengembalikan error validasi field `status` | PASS |

---

## Hasil Automated Test

Command:

```bash
php artisan test
```

Hasil aktual yang sudah diverifikasi:

```text
Tests:  19 passed (50 assertions)
Duration: 0.86s
```

Ringkasan:
- **CourseTest**: 8 skenario pengujian CRUD Course dan validasi form berhasil lolos 100%.
- **EnrollmentTest**: 9 skenario pengujian modul pendaftaran tetap berjalan normal tanpa regresi.
- **ExampleTest**: 2 skenario pengujian aksesibilitas aplikasi berhasil lolos.
- **Seluruh test project**: 19 tests passed, 50 assertions, 0 failures, 0 errors.

---

## Kesimpulan

Berdasarkan hasil pengujian otomatis dan verifikasi manual, fitur Course pada aplikasi KursusKu telah berhasil melewati seluruh skenario pengujian yang ditentukan untuk Pertemuan 10:
1. Admin dapat membuka daftar kursus dari database MySQL.
2. Admin dapat mengakses formulir tambah kursus.
3. Admin dapat menyimpan kursus baru dengan validasi ketat.
4. Data yang baru ditambahkan langsung muncul di daftar kursus.
5. Data tersimpan secara persisten ke tabel `courses` di MySQL.
