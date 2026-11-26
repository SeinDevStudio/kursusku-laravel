@extends('layouts.auth')

@section('title', 'Reset Kata Sandi - KursusKu')
@section('editorial_badge', 'Keamanan Akun')
@section('editorial_title')
    Buat Kata Sandi <span>Baru</span> yang Kuat.
@endsection
@section('editorial_desc', 'Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk menjaga keamanan akun KursusKu Anda.')

@section('content')
<div class="auth-form-header">
    <h2 class="auth-form-title">Atur Ulang Kata Sandi</h2>
    <p class="auth-form-subtitle">Masukkan email dan kata sandi baru untuk akun Anda.</p>
</div>

@if ($errors->any())
    <div class="alert alert-kursusku-danger mb-4" role="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>{{ $errors->first() }}</div>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="auth-form js-loading-form" novalidate>
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Alamat Email</label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $request->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            autocomplete="email"
            required
        >
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Kata Sandi Baru</label>
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
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
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

    <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
        <div class="auth-input-group">
            <span class="auth-input-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="form-control auth-input-control auth-input-has-toggle"
                placeholder="Ulangi kata sandi baru"
                autocomplete="new-password"
                required
            >
            <button type="button" class="auth-password-toggle js-toggle-password" data-target="password_confirmation" aria-label="Tampilkan konfirmasi kata sandi">
                <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="icon-eye-off d-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/>
                </svg>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-red w-100 py-3 justify-content-center fs-6 js-submit-btn">
        <span class="btn-label">Simpan Kata Sandi Baru</span>
        <span class="btn-spinner d-none align-items-center gap-2">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Menyimpan...
        </span>
    </button>
</form>
@endsection
