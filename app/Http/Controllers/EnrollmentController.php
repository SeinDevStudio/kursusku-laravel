<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function create(): View
    {
        $courses = CourseController::getCourses();
        return view('enrollments.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'course_id' => ['required', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'course_id.required' => 'Silakan pilih kursus yang diminati.',
        ]);

        $courses = CourseController::getCourses();
        $courseName = $courses[$validated['course_id']]['name'] ?? $validated['course_id'];

        return redirect()->route('enrollments.create')
            ->with('success', "Pendaftaran berhasil! Anda telah terdaftar pada kursus {$courseName}.")
            ->withInput();
    }
}