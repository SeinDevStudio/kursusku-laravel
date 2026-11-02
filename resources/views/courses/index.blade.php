@extends('layouts.app')

@section('title', 'Daftar Kursus - KursusKu')

@section('content')
<!-- Hero Section Compact (260px - 300px) -->
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <h1 class="courses-hero-compact-title">
                    Daftar Kursus <span>Kami</span>
                </h1>
                <p class="courses-hero-compact-desc">
                    Tingkatkan skill dan wujudkan masa depan yang lebih baik bersama KursusKu melalui kurikulum berbasis praktik industri terkini.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Course Table Content Section -->
<section class="courses-content-wrapper">
    <div class="container">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert-kursusku-success mb-4 shadow-sm" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-success flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div class="flex-grow-1">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="courses-table-card">
            <!-- Table Card Header with Title and Primary CTA Button -->
            <div class="courses-table-header">
                <div>
                    <h2 class="courses-header-title">Daftar Kursus</h2>
                    <p class="courses-header-subtitle">Kelola dan pantau seluruh data kursus yang tersedia di sistem KursusKu.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light text-dark border px-3 py-2 fw-medium rounded-pill">
                        {{ $courses->count() }} Kursus Terdaftar
                    </span>
                    <a href="{{ route('courses.create') }}" class="btn-red">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Kursus
                    </a>
                </div>
            </div>

            <!-- Clean & Spacious Table -->
            <div class="table-responsive">
                <table class="table-courses-clean">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th style="width: 120px;">Kode</th>
                            <th>Nama Kursus</th>
                            <th style="width: 160px;">Biaya</th>
                            <th class="text-center" style="width: 120px;">Kuota</th>
                            <th style="width: 150px;">Tanggal Mulai</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="course-code-pill">{{ $course->code }}</span>
                                </td>
                                <td>
                                    <div class="course-name-text">{{ $course->name }}</div>
                                    @if ($course->description)
                                        <p class="course-desc-text text-truncate">{{ $course->description }}</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($course->fee > 0)
                                        <span class="course-fee-bold">Rp {{ number_format($course->fee, 0, ',', '.') }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Gratis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="quota-tag">{{ $course->quota }} orang</span>
                                </td>
                                <td>
                                    <span class="d-inline-flex align-items-center gap-1 text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        {{ $course->start_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($course->status === 'open')
                                        <span class="status-pill status-pill-open">Dibuka</span>
                                    @elseif ($course->status === 'closed')
                                        <span class="status-pill status-pill-closed">Ditutup</span>
                                    @else
                                        <span class="status-pill status-pill-draft">Draft</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2" class="opacity-50">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        </div>
                                        <h5 class="fw-semibold text-dark mb-1">Belum Ada Data Kursus</h5>
                                        <p class="text-muted small mb-3">Tabel database masih kosong. Mulai tambahkan kursus pertama Anda.</p>
                                        <a href="{{ route('courses.create') }}" class="btn-red">
                                            + Tambah Kursus Sekarang
                                        </a>
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
