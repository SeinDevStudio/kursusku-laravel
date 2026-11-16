@extends('layouts.app')

@section('title', 'Pendaftaran Enrollment - KursusKu')

@section('content')
<!-- Hero Section (Dark, Compact) -->
<section class="enrollment-hero-compact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Registrasi Peserta</span>
                <h1 class="enrollment-hero-title">
                    Pendaftaran Kursus (Enrollment)
                </h1>
                <p class="enrollment-hero-desc">
                    Pilih peserta yang telah terdaftar dan kursus aktif yang diminati untuk membuat enrollment baru ke dalam sistem KursusKu.
                </p>
            </div>
            <div class="col-lg-4 col-12 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-light px-3 py-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    Lihat Laporan Enrollment
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Content Wrapper -->
<div class="enrollment-content-wrapper">
    <div class="container">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-kursusku-success alert-dismissible fade show mb-4" role="alert">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">Berhasil!</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="alert alert-kursusku-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <strong>Terdapat kesalahan pada formulir:</strong>
                </div>
                <ul class="mb-0 ps-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Card Container -->
        <div class="enrollment-card-wrapper">
            <div class="row g-0">
                <!-- Left Sidebar: Informasi & Panduan -->
                <div class="col-lg-5 col-12">
                    <div class="enrollment-info-sidebar">
                        <div>
                            <span class="badge-kursusku-red mb-3 d-inline-block">Informasi Relasi</span>
                            <h2 class="enrollment-info-title">
                                Pendaftaran <span>Kursus</span>
                            </h2>
                            <p class="enrollment-info-desc">
                                Formulir ini menghubungkan entitas Peserta (Participant) dengan Kursus (Course) melalui relasi Many-to-Many pada tabel Enrollment.
                            </p>

                            <ul class="enrollment-benefit-list">
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Pencegahan Duplikasi</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Setiap peserta hanya dapat mendaftar satu kali pada kursus yang sama.</div>
                                    </div>
                                </li>
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Kursus Aktif (Open)</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Hanya kursus dengan status <em>open</em> yang dapat dipilih.</div>
                                    </div>
                                </li>
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Status Terdaftar Otomatis</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Status awal enrollment secara default ditetapkan sebagai <code>registered</code>.</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="enrollment-info-badge">
                            <div class="fw-bold mb-1" style="color: #FFA3A8;">Integritas Relasional</div>
                            Koneksi data diamankan dengan Foreign Key Cascade dan Composite Unique Index di database MySQL.
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Enrollment Form -->
                <div class="col-lg-7 col-12">
                    <div class="enrollment-form-panel">
                        <h2 class="enrollment-form-title">Formulir Pendaftaran</h2>
                        <p class="enrollment-form-subtitle">Pilih participant dan course yang akan dihubungkan ke dalam tabel enrollment.</p>

                        <form method="POST" action="{{ route('enrollments.store') }}" novalidate>
                            @csrf

                            <!-- Participant Dropdown -->
                            <div class="mb-4">
                                <label for="participant_id" class="form-label fw-semibold">
                                    Participant <span class="text-danger">*</span>
                                </label>
                                <select name="participant_id"
                                        id="participant_id"
                                        class="form-select @error('participant_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Participant --</option>
                                    @foreach ($participants as $participant)
                                        <option value="{{ $participant->id }}" @selected(old('participant_id') == $participant->id)>
                                            {{ $participant->name }} ({{ $participant->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('participant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-helper-text">Pilih peserta yang terdaftar di sistem.</div>
                            </div>

                            <!-- Course Dropdown -->
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-semibold">
                                    Course <span class="text-danger">*</span>
                                </label>
                                <select name="course_id"
                                        id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Course --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                                            {{ $course->code }} — {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-helper-text">Menampilkan seluruh kursus dengan status <em>open</em>.</div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-red w-100 py-3 fs-6 mt-3 shadow-sm">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Simpan Enrollment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
