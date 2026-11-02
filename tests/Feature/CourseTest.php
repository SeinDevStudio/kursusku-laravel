<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->get('/courses/create');

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

        $response = $this->post('/courses', $payload);

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
        $response = $this->post('/courses', [
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
        $response = $this->post('/courses', [
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
        $response = $this->post('/courses', [
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
        $responseZero = $this->post('/courses', [
            'code' => 'QUOTA-0',
            'name' => 'Kursus Kuota Nol',
            'fee' => 100000,
            'quota' => 0,
            'start_date' => '2026-12-01',
            'status' => 'draft',
        ]);
        $responseZero->assertSessionHasErrors('quota');

        // Quota = 501
        $responseOver = $this->post('/courses', [
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
        $response = $this->post('/courses', [
            'code' => 'STATUS-ERR',
            'name' => 'Kursus Status Tidak Valid',
            'fee' => 100000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'archived',
        ]);

        $response->assertSessionHasErrors('status');
    }
}

