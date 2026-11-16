@extends('layouts.app')

@section('title', 'Laporan Enrollment - KursusKu')

@section('content')
<!-- Hero Section Compact -->
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <h1 class="courses-hero-compact-title">
                    Laporan Enrollment <span>KursusKu</span>
                </h1>
                <p class="courses-hero-compact-desc">
                    Memantau data pendaftaran kursus dan relasi antara peserta (Participant) dengan kursus (Course).
                </p>
            </div>
            <div class="col-lg-4 col-12 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('enrollments.create') }}" class="btn-red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Enrollment
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="courses-content-wrapper">
    <div class="container">
        <!-- Flash Message Success -->
        @if (session('success'))
            <div class="alert alert-kursusku-success alert-dismissible fade show mb-4" role="alert">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div class="flex-grow-1">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="courses-table-card">
            <!-- Header -->
            <div class="courses-table-header">
                <div>
                    <h2 class="courses-header-title">Laporan Enrollment KursusKu</h2>
                    <p class="courses-header-subtitle">Daftar pendaftaran peserta pada kursus yang terdaftar di sistem.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light text-dark border px-3 py-2 fw-medium rounded-pill">
                        {{ $enrollments->count() }} Data Enrollment
                    </span>
                    <a href="{{ route('enrollments.create') }}" class="btn-red">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Enrollment
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table-courses-clean">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Course</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $enrollment->participant->name }}</div>
                                    <div class="text-muted small">{{ $enrollment->participant->email }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $enrollment->course->name }}</div>
                                    <span class="course-code-pill mt-1">{{ $enrollment->course->code }}</span>
                                </td>
                                <td>
                                    @if ($enrollment->course->start_date)
                                        <div class="text-nowrap">{{ $enrollment->course->start_date->format('d M Y') }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 text-uppercase fw-semibold" style="font-size: 0.78rem;">
                                        {{ $enrollment->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#A3A3A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 d-block mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <p class="mb-0 fs-6 fw-medium">Belum ada enrollment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
