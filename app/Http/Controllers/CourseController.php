<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private static array $courses = [
        'WEB-01' => [
            'code' => 'WEB-01',
            'name' => 'Web Dasar',
            'remaining' => 12,
            'status' => 'open',
            'description' => 'Belajar struktur HTML dan CSS dasar.'
        ],
        'PHP-01' => [
            'code' => 'PHP-01',
            'name' => 'PHP Dasar',
            'remaining' => 8,
            'status' => 'open',
            'description' => 'Belajar variabel, function, form, dan logika PHP.'
        ],
        'LAR-01' => [
            'code' => 'LAR-01',
            'name' => 'Laravel Dasar',
            'remaining' => 4,
            'status' => 'open',
            'description' => 'Belajar route, controller, Blade, dan MVC.'
        ],
    ];

    public static function getCourses(): array
    {
        return self::$courses;
    }

    public function availability($course): JsonResponse
    {
        $lookupKey = strtoupper(trim($course));

        $slugMap = [
            '1' => 'WEB-01',
            '2' => 'PHP-01',
            '3' => 'LAR-01',
            'WEB-DASAR' => 'WEB-01',
            'PHP-DASAR' => 'PHP-01',
            'LARAVEL-DASAR' => 'LAR-01',
            'LAR-01' => 'LAR-01',
        ];

        if (isset($slugMap[$lookupKey])) {
            $lookupKey = $slugMap[$lookupKey];
        }

        if (!isset(self::$courses[$lookupKey])) {
            return response()->json([
                'message' => 'Kursus tidak ditemukan.',
                'status' => 'error'
            ], 404);
        }

        $data = self::$courses[$lookupKey];

        return response()->json([
            'course' => $data['name'],
            'code' => $data['code'],
            'remaining' => $data['remaining'],
            'status' => $data['status'],
        ]);
    }
}