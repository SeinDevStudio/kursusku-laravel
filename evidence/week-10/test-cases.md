# Test Cases — Pertemuan 10

## Pengujian CRUD Course — KursusKu

Pengujian dilakukan untuk memastikan fitur Course pada aplikasi KursusKu berjalan sesuai dengan validasi dan kebutuhan yang telah ditentukan.

|  No | Skenario Pengujian                           | Hasil yang Diharapkan                                           | Hasil Aktual                        | Status |
| --: | -------------------------------------------- | --------------------------------------------------------------- | ----------------------------------- | ------ |
|   1 | Membuka halaman daftar kursus (`index`)      | Halaman daftar kursus dapat diakses dan menampilkan data kursus | Halaman dapat diakses dengan sukses | PASS   |
|   2 | Membuka halaman tambah kursus (`create`)     | Form tambah kursus dapat diakses                                | Form dapat diakses dengan sukses    | PASS   |
|   3 | Menyimpan data kursus yang valid             | Data kursus valid berhasil disimpan ke database                 | Data berhasil dibuat dan tersimpan  | PASS   |
|   4 | Mengirim kode kursus kosong                  | Sistem menolak data dan menampilkan validasi                    | Data ditolak oleh validasi          | PASS   |
|   5 | Mengirim kode kursus lebih dari 20 karakter  | Sistem menolak kode yang melebihi batas 20 karakter             | Data ditolak oleh validasi          | PASS   |
|   6 | Mengirim nama kursus lebih dari 120 karakter | Sistem menolak nama yang melebihi batas 120 karakter            | Data ditolak oleh validasi          | PASS   |
|   7 | Mengirim kuota kursus tidak valid            | Sistem menolak nilai kuota yang tidak sesuai aturan             | Data ditolak oleh validasi          | PASS   |
|   8 | Mengirim status kursus tidak valid           | Sistem menolak status selain `draft`, `open`, atau `closed`     | Data ditolak oleh validasi          | PASS   |

## Hasil Pengujian Otomatis

Perintah yang digunakan:

```bash
php artisan test
```

Hasil aktual:

```text
Tests: 19 passed (50 assertions)
Duration: 20.41s
```

Seluruh pengujian berhasil dijalankan tanpa kegagalan.

- Total test: 19
- Test berhasil: 19
- Assertions: 50
- Failure: 0
- Error: 0

## Kesimpulan

Berdasarkan hasil pengujian, fitur Course pada aplikasi KursusKu telah berhasil melewati seluruh skenario pengujian yang dibuat untuk Pertemuan 10. Validasi input, halaman daftar kursus, halaman tambah kursus, dan proses penyimpanan data telah berjalan sesuai kebutuhan.
