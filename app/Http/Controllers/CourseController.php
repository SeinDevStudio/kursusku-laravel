<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        $lookupKey = $this->resolveCourseCode((string) $course);

        $courseData = Course::where('code', $lookupKey)->first();

        if (!$courseData) {
            // Fallback ke data statis jika belum di-seed di DB
            if (isset(self::$courses[$lookupKey])) {
                $static = self::$courses[$lookupKey];
                return response()->json([
                    'course' => $static['name'],
                    'code' => $static['code'],
                    'remaining' => $static['remaining'],
                    'status' => $static['status'],
                ]);
            }

            return response()->json([
                'message' => 'Kursus tidak ditemukan.',
                'status' => 'error',
            ], 404);
        }

        if ($courseData->status !== 'open') {
            return response()->json([
                'course' => $courseData->name,
                'code' => $courseData->code,
                'remaining' => 0,
                'status' => $courseData->status,
            ]);
        }

        return response()->json([
            'course' => $courseData->name,
            'code' => $courseData->code,
            'remaining' => $courseData->quota,
            'status' => $courseData->status,
        ]);
    }

    private function resolveCourseCode(string $course): string
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

        return $slugMap[$lookupKey] ?? $lookupKey;
    }

    public function index()
    {
        $courses = Course::latest()->get();

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:courses,code'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'fee' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'min:1', 'max:500'],
            'start_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,open,closed'],
        ], [
            'code.required' => 'Kode kursus wajib diisi.',
            'code.max' => 'Kode kursus maksimal 20 karakter.',
            'code.unique' => 'Kode kursus sudah digunakan.',
            'name.required' => 'Nama kursus wajib diisi.',
            'name.max' => 'Nama kursus maksimal 120 karakter.',
            'fee.required' => 'Biaya kursus wajib diisi.',
            'fee.integer' => 'Biaya kursus harus berupa bilangan bulat.',
            'fee.min' => 'Biaya kursus minimal 0.',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'quota.integer' => 'Kuota peserta harus berupa bilangan bulat.',
            'quota.min' => 'Kuota peserta minimal 1 orang.',
            'quota.max' => 'Kuota peserta maksimal 500 orang.',
            'start_date.required' => 'Tanggal mulai kursus wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'status.required' => 'Status kursus wajib dipilih.',
            'status.in' => 'Status kursus harus berupa draft, open, atau closed.',
        ]);

        Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Kursus berhasil ditambahkan.');
    }
}
