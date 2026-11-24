<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        Course::create([
            'code' => 'WEB-01',
            'name' => 'Web Dasar',
            'description' => 'Belajar struktur HTML dan CSS dasar.',
            'fee' => 150000,
            'quota' => 20,
            'start_date' => '2026-11-10',
            'status' => 'open',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KursusKu');
        $response->assertSee('Web Dasar');
    }

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/enrollments/create');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Kursus');
        $response->assertSee('id="course_id"', false);
    }

    public function test_registration_route_redirects_to_create(): void
    {
        $response = $this->get('/pendaftaran');
        $response->assertRedirect('/enrollments/create');
    }

    public function test_availability_endpoint_web_dasar(): void
    {
        $response = $this->getJson('/courses/WEB-01/availability');
        $response->assertStatus(200);
        $response->assertJson([
            'course' => 'Web Dasar',
            'remaining' => 12,
            'status' => 'open',
        ]);
    }

    public function test_availability_endpoint_php_dasar(): void
    {
        $response = $this->getJson('/courses/PHP-01/availability');
        $response->assertStatus(200);
        $response->assertJson([
            'course' => 'PHP Dasar',
            'remaining' => 8,
            'status' => 'open',
        ]);
    }

    public function test_availability_endpoint_laravel_dasar(): void
    {
        $response = $this->getJson('/courses/LAR-01/availability');
        $response->assertStatus(200);
        $response->assertJson([
            'course' => 'Laravel Dasar',
            'remaining' => 4,
            'status' => 'open',
        ]);
    }

    public function test_submit_valid_enrollment(): void
    {
        $participant = Participant::create([
            'name' => 'Muhammad Husein',
            'email' => 'husein@example.com',
            'phone' => '081234567890',
        ]);

        $course = Course::create([
            'code' => 'WEB001',
            'name' => 'Pemrograman Web Laravel',
            'description' => 'Materi HTML, CSS, dan Laravel',
            'fee' => 350000,
            'quota' => 25,
            'start_date' => '2026-11-15',
            'status' => 'open',
        ]);

        $response = $this->post('/enrollments', [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
        ]);

        $response->assertRedirect('/enrollments');
        $response->assertSessionHas('success', 'Enrollment berhasil disimpan.');

        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);
    }

    public function test_submit_invalid_participant_fails_validation(): void
    {
        $course = Course::create([
            'code' => 'WEB-01',
            'name' => 'Web Dasar',
            'fee' => 150000,
            'quota' => 20,
            'start_date' => '2026-11-15',
            'status' => 'open',
        ]);

        $response = $this->post('/enrollments', [
            'participant_id' => 99999,
            'course_id' => $course->id,
        ]);

        $response->assertSessionHasErrors('participant_id');
    }

    public function test_submit_duplicate_enrollment_fails_validation(): void
    {
        $participant = Participant::create([
            'name' => 'Alya Rahma',
            'email' => 'alya@example.test',
        ]);

        $course = Course::create([
            'code' => 'WEB-01',
            'name' => 'Web Dasar',
            'fee' => 150000,
            'quota' => 20,
            'start_date' => '2026-11-15',
            'status' => 'open',
        ]);

        Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $response = $this->post('/enrollments', [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
        ]);

        $response->assertSessionHasErrors('course_id');
    }

    public function test_submit_non_existent_course_code_fails_validation(): void
    {
        $response = $this->post('/enrollments', [
            'name' => 'Muhammad Husein',
            'email' => 'husein@example.com',
            'course_id' => 'KODE-TIDAK-ADA',
        ]);

        $response->assertSessionHasErrors('course_id');
        $this->assertDatabaseMissing('enrollments', [
            'name' => 'Muhammad Husein',
            'email' => 'husein@example.com',
        ]);
    }
}
