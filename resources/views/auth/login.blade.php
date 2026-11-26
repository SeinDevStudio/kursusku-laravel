@extends('layouts.auth')

@section('title', 'Masuk ke Akun - KursusKu')
@section('editorial_badge', 'Akses Portal Akun')
@section('editorial_title')
    Masuk ke Ruang Belajar <span>KursusKu</span>.
@endsection
@section('editorial_desc', 'Kelola akun pembelajaran Anda, perbarui profil, dan pantau aktivitas dengan sistem keamanan sesi terenkripsi.')

@section('content')
<div class="auth-form-header">
    <h2 class="auth-form-title">Selamat Datang Kembali</h2>
    <p class="auth-form-subtitle">Masukkan email dan kata sandi akun KursusKu Anda.</p>
</div>

@if (session('status'))
    <div class="alert alert-kursusku-success mb-4" role="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <div>{{ session('status') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-kursusku-danger mb-4" role="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

<form method="POST" action="{{ route('login.attempt') }}" class="auth-form js-loading-form" novalidate>
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Alamat Email</label>
        <div class="auth-input-group">
            <span class="auth-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
            </span>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                class="form-control auth-input-control @error('email') is-invalid @enderror"
                placeholder="nama@kursusku.test"
                autocomplete="email"
                autofocus
                required
            >
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 mb-1">
            <label for="password" class="form-label fw-semibold mb-0">Kata Sandi</label>
            <a href="{{ route('password.request') }}" class="auth-text-link small text-nowrap">Lupa kata sandi?</a>
        </div>
        <div class="auth-input-group">
            <span class="auth-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control auth-input-control auth-input-has-toggle @error('password') is-invalid @enderror"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                required
            >
            <button type="button" class="auth-password-toggle js-toggle-password" data-target="password" aria-label="Tampilkan kata sandi">
                <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="icon-eye-off d-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/>
                </svg>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
            <label class="form-check-label small text-secondary" for="remember">
                Ingat saya di perangkat ini
            </label>
        </div>
    </div>

    <button type="submit" class="btn-red w-100 py-3 justify-content-center fs-6 js-submit-btn">
        <span class="btn-label d-inline-flex align-items-center gap-2">
            Masuk Sekarang
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </span>
        <span class="btn-spinner d-none align-items-center gap-2">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memverifikasi...
        </span>
    </button>
</form>

<div class="auth-form-footer mt-4 pt-3 border-top text-center">
    <span class="text-muted small">Ingin melihat katalog terlebih dahulu?</span>
    <a href="{{ route('courses.index') }}" class="auth-text-link small ms-1">Jelajahi Daftar Kursus</a>
</div>
@endsection
