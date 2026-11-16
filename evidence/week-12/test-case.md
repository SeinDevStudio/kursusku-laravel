# TEST CASE — CASCADE DELETE

## Praktikum Laravel Pertemuan 12 — KursusKu

**Project:** KursusKu  
**Database:** MySQL — `kursusku`  
**Pengujian:** Foreign Key ON DELETE CASCADE

---

## Test Case A — Menghapus Course

**Tujuan:** Memastikan enrollment otomatis terhapus ketika course dihapus, sedangkan participant tetap tersimpan.

### Kondisi sebelum penghapusan

| Data        |  ID | Keterangan                         |
| ----------- | --: | ---------------------------------- |
| Course      |  11 | TEST-CASCADE-COURSE                |
| Participant |   5 | cascade.course@example.test        |
| Enrollment  |   5 | participant_id = 5, course_id = 11 |

Jumlah enrollment yang terhubung: **1 record**.

### Aksi pengujian

```php
App\Models\Course::find(11)->delete();
```

### Hasil setelah penghapusan

| Pemeriksaan      | Hasil             |
| ---------------- | ----------------- |
| Course ID 11     | Terhapus          |
| Enrollment ID 5  | Terhapus otomatis |
| Participant ID 5 | Tetap ada         |

### Output hasil pengujian

```json
{
    "test": "Cascade Test A: Menghapus Course Dummy",
    "course_id": 11,
    "participant_id": 5,
    "enrollment_id": 5,
    "initial_enrollment_count": 1,
    "course_deleted": true,
    "enrollment_deleted": true,
    "participant_retained_after_course_deletion": true,
    "pass": true
}
```

**Status: PASS**

---

## Test Case B — Menghapus Participant

**Tujuan:** Memastikan enrollment otomatis terhapus ketika participant dihapus, sedangkan course tetap tersimpan.

### Kondisi sebelum penghapusan

| Data        |  ID | Keterangan                         |
| ----------- | --: | ---------------------------------- |
| Course      |  12 | TEST-CASCADE-PART                  |
| Participant |   6 | cascade.participant@example.test   |
| Enrollment  |   6 | participant_id = 6, course_id = 12 |

Jumlah enrollment yang terhubung: **1 record**.

### Aksi pengujian

```php
App\Models\Participant::find(6)->delete();
```

### Hasil setelah penghapusan

| Pemeriksaan      | Hasil                     |
| ---------------- | ------------------------- |
| Participant ID 6 | Terhapus                  |
| Enrollment ID 6  | Terhapus otomatis         |
| Course ID 12     | Tetap ada saat verifikasi |

Course dummy ID 12 kemudian dibersihkan setelah pengujian selesai.

### Output hasil pengujian

```json
{
    "test": "Cascade Test B: Menghapus Participant Dummy",
    "course_id": 12,
    "participant_id": 6,
    "enrollment_id": 6,
    "initial_enrollment_count": 1,
    "participant_deleted": true,
    "enrollment_deleted": true,
    "course_retained_after_participant_deletion": true,
    "course_cleaned_up": true,
    "pass": true
}
```

**Status: PASS**

---

## Kesimpulan

Kedua pengujian menunjukkan bahwa aturan foreign key `ON DELETE CASCADE` berjalan sesuai harapan.

1. Menghapus Course menghapus Enrollment terkait tanpa menghapus Participant.
2. Menghapus Participant menghapus Enrollment terkait tanpa menghapus Course.

**Hasil keseluruhan: 2 dari 2 pengujian PASS.**

Catatan: Dokumentasi ini disusun berdasarkan hasil eksekusi pengujian Tahap 4 yang dipulihkan dari riwayat runtime. Bukan hasil eksekusi ulang pada saat dokumentasi dibuat.
