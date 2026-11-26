@extends('layouts.app')

@section('title', 'Ubah Kata Sandi - KursusKu')

@section('content')
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center gy-3">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Keamanan Akun</span>
                <h1 class="courses-hero-compact-title">Ubah <span>Kata Sandi</span></h1>
                <p class="courses-hero-compact-desc">Pastikan akun Anda selalu terlindungi dengan kata sandi yang kuat.</p>
            </div>
            <div class="col-lg-4 col-12 text-lg-end">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light px-3 py-2">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="courses-content-wrapper">
    <div class="container">
        <div class="create-course-container">
            @if (session('success'))
                <div class="alert alert-kursusku-success mb-4" role="alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="create-form-card">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Kata Sandi Lama <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top">
                        <a href="{{ route('profile.edit') }}" class="btn-secondary-clean">Kembali ke Edit Profil</a>
                        <button type="submit" class="btn-red">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
