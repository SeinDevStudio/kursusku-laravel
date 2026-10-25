@extends('layouts.app')

@section('title', $siteName . ' - Bangun Skill Web Bersama KursusKu')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-12">
                <span class="hero-label">Belajar Web Lebih Terarah</span>
                <h1 class="hero-title">Bangun Skill Web Bersama {{ $siteName }}</h1>
                <p class="hero-desc">
                    Pelajari langkah demi langkah dari konsep dasar HTML, CSS, logika pemrograman PHP, hingga arsitektur modern menggunakan Laravel.
                </p>
                @if (Route::has('enrollments.create'))
                    <a href="{{ route('enrollments.create') }}" class="btn-red">Daftar Kursus</a>
                @else
                    <a href="#courses" class="btn-red">Daftar Kursus</a>
                @endif
            </div>
            <div class="col-lg-5 col-md-12 hero-img-box">
                <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Pembelajaran Web KursusKu" class="hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Course Section -->
<section class="py-5" id="courses">
    <div class="container">
        <div class="section-title-wrap">
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection