@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - KursusKu')
@section('editorial_badge', 'Pemulihan Akun')
@section('editorial_title')
    Pulihkan Akses <span>Akun</span> Anda.
@endsection
@section('editorial_desc', 'Kami akan membuatkan tautan reset kata sandi yang aman dengan masa berlaku terbatas melalui mekanisme Password Broker.')

@section('content')
<div class="auth-form-header">
    <h2 class="auth-form-title">Lupa Kata Sandi?</h2>
    <p class="auth-form-subtitle">Masukkan alamat email terdaftar untuk menerima tautan pengaturan ulang kata sandi.</p>
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

<form method="POST" action="{{ route('password.email') }}" class="auth-form js-loading-form" novalidate>
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label fw-semibold">Alamat Email Terdaftar</label>
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

    <button type="submit" class="btn-red w-100 py-3 justify-content-center fs-6 js-submit-btn">
        <span class="btn-label d-inline-flex align-items-center gap-2">
            Kirim Tautan Reset Password
        </span>
        <span class="btn-spinner d-none align-items-center gap-2">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memproses...
        </span>
    </button>
</form>

<div class="auth-form-footer mt-4 pt-3 border-top text-center">
    <a href="{{ route('login') }}" class="auth-text-link small d-inline-flex align-items-center gap-1">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
        </svg>
        Kembali ke Halaman Login
    </a>
</div>
@endsection
