<?php

namespace Tests\Feature;

use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    public function test_home_page_is_accessible(): void
    {
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
        $response = $this->post('/enrollments', [
            'name' => 'Muhammad Husein',
            'email' => 'husein@example.com',
            'course_id' => 'WEB-01',
        ]);

        $response->assertRedirect('/enrollments/create');
        $response->assertSessionHas('success');
    }

    public function test_submit_invalid_email_fails_validation(): void
    {
        $response = $this->post('/enrollments', [
            'name' => 'Muhammad Husein',
            'email' => 'bukan-email-valid',
            'course_id' => 'WEB-01',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_submit_invalid_name_fails_validation(): void
    {
        $response = $this->post('/enrollments', [
            'name' => 'Al',
            'email' => 'husein@example.com',
            'course_id' => 'WEB-01',
        ]);

        $response->assertSessionHasErrors('name');
    }
}