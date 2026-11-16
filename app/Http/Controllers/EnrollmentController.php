<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::with(['participant', 'course'])->latest()->get();

        return view('enrollments.index', compact('enrollments'));
    }

    public function create(): View
    {
        $participants = Participant::orderBy('name')->get();
        $courses = Course::where('status', 'open')
            ->orderBy('name')
            ->get();

        return view('enrollments.create', compact('participants', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($query) use ($request) {
                    return $query->where('participant_id', $request->participant_id);
                }),
            ],
        ], [
            'participant_id.required' => 'Silakan pilih participant.',
            'participant_id.exists' => 'Participant yang dipilih tidak valid.',
            'course_id.required' => 'Silakan pilih course.',
            'course_id.exists' => 'Course yang dipilih tidak valid.',
            'course_id.unique' => 'Participant ini sudah terdaftar pada course tersebut.',
        ]);

        Enrollment::create([
            'participant_id' => $validated['participant_id'],
            'course_id' => $validated['course_id'],
            'status' => 'registered',
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment berhasil disimpan.');
    }
}
