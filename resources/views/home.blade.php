@extends('layouts.app')

@section('title', $siteName . ' - Bangun Skill Web Bersama KursusKu')

@section('content')
<!-- Hero Section (Balanced 2-Column, 520px Height) -->
<section class="home-hero-section">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 col-12">
                <div class="hero-pill-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 1-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                    </svg>
                    Belajar Web Lebih Terarah
                </div>
                
                <h1 class="home-hero-title">
                    Bangun Skill Web Bersama <span>{{ $siteName }}</span>
                </h1>
                
                <p class="home-hero-desc">
                    Pelajari berbagai keterampilan web mulai dari HTML, CSS, logika pemrograman, PHP, hingga Laravel secara bertahap dan terstruktur.
                </p>
                
                <div class="home-hero-actions">
                    <a href="{{ route('courses.index') }}" class="btn-red">
                        Lihat Kursus
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="#keunggulan" class="btn-secondary-clean">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-12 text-center text-lg-end">
                <div class="home-hero-image-box">
                    <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Pembelajaran Web KursusKu" class="home-hero-img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kursus Pilihan Section -->
<section class="py-5" id="courses">
    <div class="container py-3">
        <div class="section-header-wrap">
            <span class="section-badge-label">Katalog Unggulan</span>
            <h2 class="section-main-heading">Kursus Pilihan</h2>
            <p class="section-desc-sub">Materi terstruktur yang dirancang bertahap untuk membantu proses belajar Anda.</p>
        </div>

        <div class="row g-4">
            @forelse ($courses as $course)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="course-card-item">
                        <div class="course-card-top">
                            <span class="course-card-badge">{{ $course->code }}</span>
                            @if ($course->status === 'open')
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle small px-2 py-1">
                                    Tersedia
                                </span>
                            @elseif ($course->status === 'closed')
                                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle small px-2 py-1">
                                    Ditutup
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle small px-2 py-1">
                                    Draft
                                </span>
                            @endif
                        </div>
                        <h3 class="course-card-title">{{ $course->name }}</h3>
                        <p class="course-card-desc">{{ $course->description }}</p>
                        <div class="course-card-footer">
                            <span class="text-muted small fw-medium">Kurikulum Praktis</span>
                            <a href="{{ route('enrollments.create') }}" class="text-danger fw-semibold text-decoration-none d-inline-flex align-items-center gap-1">
                                Daftar
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-4 rounded-3 border bg-white shadow-sm mx-auto" style="max-width: 500px;">
                        <p class="text-muted mb-0">Belum ada kursus yang tersedia saat ini. Silakan cek kembali nanti.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Keunggulan Section (3 Features) -->
<section class="features-section" id="keunggulan">
    <div class="container">
        <div class="section-header-wrap text-center mx-auto" style="max-width: 680px;">
            <span class="section-badge-label">Keunggulan Platform</span>
            <h2 class="section-main-heading">Kenapa Belajar di KursusKu?</h2>
            <p class="section-desc-sub mx-auto">Kami menyediakan pengalaman belajar yang fokus pada pemahaman konsep dan kemampuan membuat karya nyata.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 col-12">
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Materi Terstruktur</h3>
                    <p class="feature-desc">Materi disusun secara bertahap dan teratur agar mudah dipahami, mulai dari fondasi hingga implementasi sistem utuh.</p>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75 16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Pembelajaran Praktis</h3>
                    <p class="feature-desc">Belajar melalui contoh studi kasus riil dan praktik langsung, sehingga Anda tidak hanya paham teori tapi mampu mengeksekusi.</p>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Skill yang Relevan</h3>
                    <p class="feature-desc">Materi kurikulum disesuaikan dengan kebutuhan pengembangan web modern dan standar industri terkini.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action (CTA) Section -->
<section class="cta-banner-section text-center">
    <div class="container">
        <div class="mx-auto" style="max-width: 650px;">
            <h2 class="cta-banner-title">Siap Meningkatkan Skill Web Kamu?</h2>
            <p class="cta-banner-desc mx-auto">
                Mulai belajar bersama KursusKu sekarang dan bangun portofolio proyek web modern yang kompetitif.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="{{ route('courses.index') }}" class="btn-red">
                    Lihat Semua Kursus
                </a>
                <a href="{{ route('enrollments.create') }}" class="btn-outline-red">
                    Pendaftaran Kursus
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
