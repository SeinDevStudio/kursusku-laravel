<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): self
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        return $this->actingAs($admin);
    }

    /**
     * Test 1: GET /courses berhasil menampilkan daftar kursus.
     */
    public function test_index_page_is_accessible_and_displays_courses(): void
    {
        Course::create([
            'code' => 'TEST-01',
            'name' => 'Belajar TDD Laravel',
            'description' => 'Materi testing dasar',
            'fee' => 150000,
            'quota' => 25,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $response = $this->get('/courses');

        $response->assertStatus(200);
        $response->assertSee('Daftar Kursus');
        $response->assertSee('TEST-01');
        $response->assertSee('Belajar TDD Laravel');
        $response->assertSee('Rp 150.000');
    }

    /**
     * Test 2: GET /courses/create berhasil menampilkan formulir tambah kursus.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAsAdmin()->get('/courses/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Kursus Baru');
        $response->assertSee('name="code"', false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="quota"', false);
        $response->assertSee('name="fee"', false);
    }

    /**
     * Test 3: POST valid dapat membuat Course baru di database.
     */
    public function test_store_valid_course_successfully_creates_record(): void
    {
        $payload = [
            'code' => 'LAR-101',
            'name' => 'Laravel Masterclass',
            'description' => 'Panduan lengkap arsitektur Laravel',
            'fee' => 300000,
            'quota' => 30,
            'start_date' => '2026-12-15',
            'status' => 'open',
        ];

        $response = $this->actingAsAdmin()->post('/courses', $payload);

        $response->assertRedirect('/courses');
        $response->assertSessionHas('success', 'Kursus berhasil ditambahkan.');

        $this->assertDatabaseHas('courses', [
            'code' => 'LAR-101',
            'name' => 'Laravel Masterclass',
            'fee' => 300000,
            'quota' => 30,
            'status' => 'open',
        ]);
    }

    /**
     * Test 4: Validation menolak code kosong.
     */
    public function test_validation_rejects_empty_code(): void
    {
        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => '',
            'name' => 'Kursus Tanpa Kode',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Test 5: Validation menolak code lebih dari 20 karakter.
     */
    public function test_validation_rejects_code_longer_than_20_chars(): void
    {
        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => 'KODE-SANGAT-PANJANG-LEBIH-20-KARAKTER',
            'name' => 'Kursus Kode Panjang',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Test 6: Validation menolak name lebih dari 120 karakter.
     */
    public function test_validation_rejects_name_longer_than_120_chars(): void
    {
        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => 'NAME-ERR',
            'name' => str_repeat('A', 121),
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test 7: Validation menolak quota 0 atau di luar aturan (min: 1, max: 500).
     */
    public function test_validation_rejects_invalid_quota(): void
    {
        // Quota = 0
        $responseZero = $this->actingAsAdmin()->post('/courses', [
            'code' => 'QUOTA-0',
            'name' => 'Kursus Kuota Nol',
            'fee' => 100000,
            'quota' => 0,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);
        $responseZero->assertSessionHasErrors('quota');

        // Quota = 501
        $responseOver = $this->actingAsAdmin()->post('/courses', [
            'code' => 'QUOTA-501',
            'name' => 'Kursus Kuota Berlebih',
            'fee' => 100000,
            'quota' => 501,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);
        $responseOver->assertSessionHasErrors('quota');
    }

    /**
     * Test 8: Validation menolak status selain draft/open/closed.
     */
    public function test_validation_rejects_invalid_status(): void
    {
        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => 'STATUS-ERR',
            'name' => 'Kursus Status Tidak Valid',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'archived',
        ]);

        $response->assertSessionHasErrors('status');
    }

    /**
     * Test 9: GET /courses/{course} berhasil menampilkan halaman detail Course.
     */
    public function test_show_page_is_accessible_and_displays_course_details(): void
    {
        $course = Course::create([
            'code' => 'SHOW-01',
            'name' => 'Detail Course Exploration',
            'description' => 'Mempelajari detail routing dan model binding',
            'fee' => 250000,
            'quota' => 15,
            'start_date' => '2026-12-10',
            'status' => 'open',
        ]);

        $response = $this->get('/courses/' . $course->id);

        $response->assertStatus(200);
        $response->assertSee('SHOW-01');
        $response->assertSee('Detail Course Exploration');
        $response->assertSee('250.000');
        $response->assertSee('15 orang');
        $response->assertSee('Dibuka');
    }

    /**
     * Test 10: GET /courses/{id} dengan ID tidak valid menghasilkan 404 (Route Model Binding).
     */
    public function test_show_non_existent_course_returns_404(): void
    {
        $response = $this->get('/courses/99999');

        $response->assertStatus(404);
    }

    /**
     * Test 11: POST /courses menolak duplicate code yang sudah ada di database.
     */
    public function test_store_rejects_duplicate_code(): void
    {
        Course::create([
            'code' => 'DUP-01',
            'name' => 'Kursus Asli',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => 'DUP-01',
            'name' => 'Kursus Duplikat',
            'fee' => 120000,
            'quota' => 25,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ]);

        $response->assertSessionHasErrors('code');
    }

    /**
     * Test 12: Boundary validasi kuota 500 valid dan 501 ditolak.
     */
    public function test_quota_boundary_500_valid_and_501_invalid(): void
    {
        // 500 Valid
        $response500 = $this->actingAsAdmin()->post('/courses', [
            'code' => 'MAX-500',
            'name' => 'Kursus Kuota Maksimal',
            'fee' => 200000,
            'quota' => 500,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);
        $response500->assertRedirect('/courses');
        $this->assertDatabaseHas('courses', ['code' => 'MAX-500', 'quota' => 500]);

        // 501 Invalid
        $response501 = $this->actingAsAdmin()->post('/courses', [
            'code' => 'MAX-501',
            'name' => 'Kursus Melebihi Maksimal',
            'fee' => 200000,
            'quota' => 501,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);
        $response501->assertSessionHasErrors('quota');
    }

    /**
     * Test 13: POST /courses menolak format start_date yang tidak valid.
     */
    public function test_validation_rejects_invalid_start_date(): void
    {
        $response = $this->actingAsAdmin()->post('/courses', [
            'code' => 'DATE-ERR',
            'name' => 'Kursus Tanggal Rusak',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => 'bukan-format-tanggal-valid',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('start_date');
    }

    /**
     * Test 14: GET /courses/{course}/edit berhasil menampilkan form edit dan data saat ini.
     */
    public function test_edit_page_is_accessible_and_displays_current_data(): void
    {
        $course = Course::create([
            'code' => 'EDIT-01',
            'name' => 'Kursus Siap Diedit',
            'description' => 'Deskripsi sebelum update',
            'fee' => 175000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->actingAsAdmin()->get('/courses/' . $course->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Kursus');
        $response->assertSee('EDIT-01');
        $response->assertSee('Kursus Siap Diedit');
        $response->assertSee('175000');
    }

    /**
     * Test 15: PUT /courses/{course} update normal tanpa mengubah code sendiri tetap berhasil (unique ignore self).
     */
    public function test_update_course_keeping_same_code_succeeds(): void
    {
        $course = Course::create([
            'code' => 'KEEP-01',
            'name' => 'Nama Lama',
            'description' => 'Deskripsi Lama',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->actingAsAdmin()->put('/courses/' . $course->id, [
            'code' => 'KEEP-01', // code tetap sama
            'name' => 'Nama Baru Diperbarui',
            'description' => 'Deskripsi Baru Diperbarui',
            'fee' => 250000,
            'quota' => 35,
            'start_date' => '2026-12-10',
            'status' => 'open',
        ]);

        $response->assertRedirect('/courses');
        $response->assertSessionHas('success', 'Kursus berhasil diperbarui.');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'code' => 'KEEP-01',
            'name' => 'Nama Baru Diperbarui',
            'fee' => 250000,
            'quota' => 35,
            'status' => 'open',
        ]);
    }

    /**
     * Test 16: PUT /courses/{course} menolak jika code diubah menjadi code milik Course lain.
     */
    public function test_update_course_with_duplicate_code_of_another_course_fails(): void
    {
        $courseA = Course::create([
            'code' => 'COURSE-A',
            'name' => 'Kursus A',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $courseB = Course::create([
            'code' => 'COURSE-B',
            'name' => 'Kursus B',
            'fee' => 150000,
            'quota' => 25,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ]);

        // Coba ubah Course B agar memakai code 'COURSE-A'
        $response = $this->actingAsAdmin()->put('/courses/' . $courseB->id, [
            'code' => 'COURSE-A',
            'name' => 'Kursus B Menggunakan Kode A',
            'fee' => 150000,
            'quota' => 25,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ]);

        $response->assertSessionHasErrors('code');

        // Pastikan Course B di database kodenya tidak berubah
        $this->assertDatabaseHas('courses', [
            'id' => $courseB->id,
            'code' => 'COURSE-B',
        ]);
    }

    /**
     * Test 17: DELETE /courses/{course} berhasil menghapus record Course dummy dari database.
     */
    public function test_destroy_course_successfully_deletes_record(): void
    {
        $course = Course::create([
            'code' => 'DEL-01',
            'name' => 'Kursus Target Hapus',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('courses', ['id' => $course->id, 'code' => 'DEL-01']);

        $response = $this->actingAsAdmin()->delete('/courses/' . $course->id);

        $response->assertRedirect('/courses');
        $response->assertSessionHas('success', 'Kursus berhasil dihapus.');

        $this->assertDatabaseMissing('courses', ['id' => $course->id, 'code' => 'DEL-01']);
    }

    /**
     * Test 18: Pencarian berdasarkan kode Course (?q=...).
     */
    public function test_search_courses_by_code(): void
    {
        Course::create([
            'code' => 'WEB-101',
            'name' => 'HTML Dasar',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'PYT-202',
            'name' => 'Python Dasar',
            'fee' => 150000,
            'quota' => 20,
            'start_date' => '2026-12-02',
            'status' => 'open',
        ]);

        $response = $this->get('/courses?q=PYT-202');

        $response->assertStatus(200);
        $response->assertSee('PYT-202');
        $response->assertSee('Python Dasar');
        $response->assertDontSee('WEB-101');
    }

    /**
     * Test 19: Pencarian berdasarkan nama Course (?q=...).
     */
    public function test_search_courses_by_name(): void
    {
        Course::create([
            'code' => 'LAR-301',
            'name' => 'Laravel Lanjutan',
            'fee' => 250000,
            'quota' => 15,
            'start_date' => '2026-12-03',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'SQL-101',
            'name' => 'Database MySQL',
            'fee' => 120000,
            'quota' => 20,
            'start_date' => '2026-12-04',
            'status' => 'open',
        ]);

        $response = $this->get('/courses?q=Laravel');

        $response->assertStatus(200);
        $response->assertSee('LAR-301');
        $response->assertSee('Laravel Lanjutan');
        $response->assertDontSee('SQL-101');
    }

    /**
     * Test 20: Filter status open.
     */
    public function test_filter_courses_by_status_open(): void
    {
        Course::create([
            'code' => 'OPEN-01',
            'name' => 'Kursus Terbuka',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'CLOSED-01',
            'name' => 'Kursus Tutup',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'closed',
        ]);

        Course::create([
            'code' => 'DRAFT-01',
            'name' => 'Kursus Draft',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->get('/courses?status=open');

        $response->assertStatus(200);
        $response->assertSee('OPEN-01');
        $response->assertDontSee('CLOSED-01');
        $response->assertDontSee('DRAFT-01');
    }

    /**
     * Test 21: Filter status closed.
     */
    public function test_filter_courses_by_status_closed(): void
    {
        Course::create([
            'code' => 'OPEN-02',
            'name' => 'Kursus Terbuka 2',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'CLOSED-02',
            'name' => 'Kursus Tutup 2',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'closed',
        ]);

        $response = $this->get('/courses?status=closed');

        $response->assertStatus(200);
        $response->assertSee('CLOSED-02');
        $response->assertDontSee('OPEN-02');
    }

    /**
     * Test 22: Filter status draft.
     */
    public function test_filter_courses_by_status_draft(): void
    {
        Course::create([
            'code' => 'OPEN-03',
            'name' => 'Kursus Terbuka 3',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'DRAFT-03',
            'name' => 'Kursus Draft 3',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);

        $response = $this->get('/courses?status=draft');

        $response->assertStatus(200);
        $response->assertSee('DRAFT-03');
        $response->assertDontSee('OPEN-03');
    }

    /**
     * Test 23: Kombinasi pencarian q dan filter status.
     */
    public function test_search_and_filter_combination(): void
    {
        Course::create([
            'code' => 'WEB-OPEN',
            'name' => 'Web Programming Open',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        Course::create([
            'code' => 'WEB-CLOSED',
            'name' => 'Web Programming Closed',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'closed',
        ]);

        Course::create([
            'code' => 'MOB-OPEN',
            'name' => 'Mobile Programming Open',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $response = $this->get('/courses?q=Programming&status=open');

        $response->assertStatus(200);
        $response->assertSee('WEB-OPEN');
        $response->assertSee('MOB-OPEN');
        $response->assertDontSee('WEB-CLOSED');
    }

    /**
     * Test 24: Pagination maksimal 10 data per halaman.
     */
    public function test_courses_pagination_limits_to_10_per_page(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Course::create([
                'code' => sprintf('CRS-%02d', $i),
                'name' => "Kursus Ke-{$i}",
                'fee' => 100000,
                'quota' => 20,
                'start_date' => '2026-12-01',
                'status' => 'open',
            ]);
        }

        $response = $this->get('/courses');

        $response->assertStatus(200);
        $response->assertViewHas('courses', function ($courses) {
            return $courses->count() === 10 && $courses->total() === 12;
        });
        $response->assertSee('12 Kursus Terdaftar');
    }

    /**
     * Test 25: Parameter pencarian dan filter tetap dipertahankan pada pagination (withQueryString).
     */
    public function test_pagination_preserves_search_and_filter_query_string(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Course::create([
                'code' => sprintf('LAR-%02d', $i),
                'name' => "Laravel Batch {$i}",
                'fee' => 100000,
                'quota' => 20,
                'start_date' => '2026-12-01',
                'status' => 'open',
            ]);
        }

        $response = $this->get('/courses?q=Laravel&status=open');

        $response->assertStatus(200);
        $response->assertSee('q=Laravel', false);
        $response->assertSee('status=open', false);

        $page2 = $this->get('/courses?q=Laravel&status=open&page=2');
        $page2->assertStatus(200);
        $page2->assertViewHas('courses', function ($courses) {
            return $courses->count() === 2 && $courses->currentPage() === 2;
        });
    }

    /**
     * Test 26: Empty state tampil ketika pencarian atau filter tidak menemukan hasil.
     */
    public function test_empty_state_displays_when_search_has_no_results(): void
    {
        Course::create([
            'code' => 'EXIST-01',
            'name' => 'Kursus Tersedia',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $response = $this->get('/courses?q=TIDAK_ADA_SAMA_SEKALI');

        $response->assertStatus(200);
        $response->assertSee('Kursus Tidak Ditemukan');
        $response->assertSee('Hapus Pencarian &amp; Filter', false);
    }
}
