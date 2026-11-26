@extends('layouts.app')

@section('title', 'Edit Profil - KursusKu')

@section('content')
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center gy-3">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Pengaturan Akun</span>
                <h1 class="courses-hero-compact-title">Edit <span>Profil</span></h1>
                <p class="courses-hero-compact-desc">Perbarui identitas akun dan foto profil pribadi Anda.</p>
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

            @if ($errors->any())
                <div class="alert alert-kursusku-danger mb-4" role="alert">
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">Periksa kembali input Anda:</strong>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="create-form-card">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="d-flex flex-wrap align-items-center gap-4 pb-4 mb-4 border-bottom">
                        <div class="dashboard-avatar-wrap mb-0">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="Preview foto profil" class="dashboard-avatar-img js-avatar-preview">
                            @else
                                <img src="" alt="Preview foto profil" class="dashboard-avatar-img js-avatar-preview d-none">
                                <div class="dashboard-avatar-fallback js-avatar-fallback">{{ $user->initials }}</div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <label for="profile_photo" class="form-label fw-semibold">Foto Profil</label>
                            <input
                                type="file"
                                name="profile_photo"
                                id="profile_photo"
                                accept=".jpg,.jpeg,.png,.webp,image/*"
                                class="form-control @error('profile_photo') is-invalid @enderror js-avatar-input"
                            >
                            <div class="form-text">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</div>
                            @error('profile_photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror" placeholder="contoh: alya_rahma">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('profile.password') }}" class="btn-secondary-clean">Ke Pengaturan Kata Sandi</a>
                        <button type="submit" class="btn-red">Simpan Perubahan Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
