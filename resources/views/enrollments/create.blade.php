@extends('layouts.app')

@section('title', 'Pendaftaran Kursus - KursusKu')

@section('content')
<!-- Hero Section (Dark, Compact ~220-280px) -->
<section class="enrollment-hero-compact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Registrasi Siswa</span>
                <h1 class="enrollment-hero-title">
                    Pendaftaran Kursus
                </h1>
                <p class="enrollment-hero-desc">
                    Pilih kursus yang ingin Anda ikuti, amankan kursi Anda, dan mulai bangun keahlian web modern bersama instruktur berpengalaman.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Content Wrapper with 40px clean spacing -->
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
                    <strong class="d-block mb-1">Pendaftaran Berhasil!</strong>
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
                    <strong>Terdapat kesalahan pada pengisian formulir:</strong>
                </div>
                <ul class="mb-0 ps-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Card Container: 2 Columns on Desktop, 1 Column on Mobile -->
        <div class="enrollment-card-wrapper">
            <div class="row g-0">
                <!-- Left Sidebar: Kenapa KursusKu -->
                <div class="col-lg-5 col-12">
                    <div class="enrollment-info-sidebar">
                        <div>
                            <span class="badge-kursusku-red mb-3 d-inline-block">Keuntungan Belajar</span>
                            <h2 class="enrollment-info-title">
                                Kenapa Belajar di <span>KursusKu</span>?
                            </h2>
                            <p class="enrollment-info-desc">
                                Kurikulum dirancang sesuai standar industri digital terkini untuk membekali Anda dengan keahlian praktis yang langsung siap kerja.
                            </p>

                            <ul class="enrollment-benefit-list">
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Materi Terstruktur & Praktis</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Kurikulum berbasis project nyata dan studi kasus industri.</div>
                                    </div>
                                </li>
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Bimbingan Langsung</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Konsultasi kode dan review tugas bersama mentor berpengalaman.</div>
                                    </div>
                                </li>
                                <li class="enrollment-benefit-item">
                                    <svg class="enrollment-benefit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Sertifikat Resmi Kelulusan</strong>
                                        <div class="small mt-1" style="color: #A3A3A3 !important;">Dapat diverifikasi online untuk memperkuat portofolio Anda.</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="enrollment-info-badge">
                            <div class="fw-bold mb-1" style="color: #FFA3A8;">Garansi Kursi Terjamin</div>
                            Sistem kuota kami sinkron secara real-time. Begitu pendaftaran diterima, kursi Anda langsung diamankan.
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Enrollment Form -->
                <div class="col-lg-7 col-12">
                    <div class="enrollment-form-panel">
                        <h2 class="enrollment-form-title">Formulir Pendaftaran</h2>
                        <p class="enrollment-form-subtitle">Lengkapi identitas Anda untuk memesan kursi pada kelas pilihan.</p>

                        <form method="POST" action="{{ route('enrollments.store') }}" novalidate>
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Contoh: Muhammad Husein"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="nama@domain.com"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-helper-text">Informasi akun dan akses kelas akan dikirimkan ke email ini.</div>
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Pilihan Kursus <span class="text-danger">*</span></label>
                                <select name="course_id"
                                        id="course_id"
                                        class="form-select @error('course_id') is-invalid @enderror"
                                        data-availability-template="{{ url('/courses/__COURSE__/availability') }}"
                                        required>
                                    <option value="">-- Pilih Kursus yang Ingin Diikuti --</option>
                                    @foreach ($courses as $c)
                                        <option
                                            value="{{ $c->code }}"
                                            @selected(old('course_id') == $c->code)
                                        >
                                            {{ $c->code }} — {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Availability Ajax Feedback Box -->
                            <div id="availability" class="alert alert-secondary my-3" aria-live="polite">
                                Pilih kursus untuk melihat sisa kursi melalui Ajax.
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-red w-100 py-3 fs-6 mt-3 shadow-sm">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                Daftar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
