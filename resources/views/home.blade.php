@extends('layouts.app')

@section('title', $siteName . ' - Bangun Skill Web Bersama KursusKu')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-12 hero-content">
                <span class="hero-label anim-fade-in">Belajar Web Lebih Terarah</span>
                <h1 class="hero-title anim-slide-up-1">Bangun Skill Web Bersama {{ $siteName }}</h1>
                <p class="hero-desc anim-slide-up-2">
                    Pelajari langkah demi langkah dari konsep dasar HTML, CSS, logika pemrograman PHP, hingga arsitektur modern menggunakan Laravel framework.
                </p>
                <div class="hero-actions anim-slide-up-3">
                    <a href="{{ route('enrollments.create') }}" class="btn-red">Daftar Kursus</a>
                    <a href="#courses" class="btn-outline-red ms-2">Lihat Kursus</a>
                </div>
            </div>
            <div class="col-lg-5 col-md-12 hero-img-box anim-fade-in-right">
                <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Pembelajaran Web KursusKu" class="hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Course Section -->
<section class="py-5" id="courses">
    <div class="container">
        <div class="section-title-wrap text-start">
            <h2 class="section-title">Kursus Pilihan</h2>
            <p class="section-subtitle">Materi terstruktur yang dirancang bertahap untuk mahasiswa dan pemula.</p>
        </div>

        <div class="row g-4">
            @foreach ($featured as $course)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="course-card">
                        <span class="course-badge">{{ $course['code'] }}</span>
                        <h3 class="course-name">{{ $course['name'] }}</h3>
                        <p class="course-desc">{{ $course['description'] }}</p>
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="badge bg-dark text-white fw-normal px-2 py-1">Tersedia</span>
                            <a href="{{ route('enrollments.create') }}" class="text-danger fw-semibold text-decoration-none small card-link">
                                Daftar &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection