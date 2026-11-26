@extends('layouts.app')

@section('title', 'Dashboard Akun - KursusKu')

@section('content')
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center gy-3">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Dashboard Personal</span>
                <h1 class="courses-hero-compact-title">
                    Halo, <span>{{ $user->name }}</span>
                </h1>
                <p class="courses-hero-compact-desc">
                    Kelola informasi akun, foto profil, dan keamanan kata sandi Anda dari satu tempat.
                </p>
            </div>
            <div class="col-lg-4 col-12 text-lg-end">
                @if ($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn-red">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        Buka Admin Panel
                    </a>
                @else
                    <a href="{{ route('courses.index') }}" class="btn-red">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                        </svg>
                        Jelajahi Kursus
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="courses-content-wrapper">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-kursusku-success mb-4" role="alert">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div class="flex-grow-1">{{ session('success') }}</div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Summary Card -->
            <div class="col-lg-4 col-12">
                <div class="dashboard-profile-card">
                    <div class="dashboard-avatar-wrap">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" class="dashboard-avatar-img">
                        @else
                            <div class="dashboard-avatar-fallback">{{ $user->initials }}</div>
                        @endif
                    </div>

                    <h2 class="h5 fw-bold text-dark mb-1">{{ $user->name }}</h2>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        @if ($user->isAdmin())
                            <span class="badge-kursusku-red">ADMIN</span>
                        @else
                            <span class="badge bg-dark text-white px-3 py-1 rounded-1">USER</span>
                        @endif
                        <span class="status-pill status-pill-open">Aktif</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn-red justify-content-center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                            </svg>
                            Edit Profil
                        </a>
                        <a href="{{ route('profile.password') }}" class="btn-secondary-clean justify-content-center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Ubah Kata Sandi
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2 fw-semibold">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Account Details & Shortcuts -->
            <div class="col-lg-8 col-12">
                <div class="courses-table-card mb-4">
                    <div class="courses-table-header">
                        <div>
                            <h2 class="courses-header-title">Informasi Akun</h2>
                            <p class="courses-header-subtitle">Detail identitas dan catatan aktivitas sesi akun Anda.</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Nama Lengkap</div>
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Username</div>
                                    <div class="fw-semibold text-dark">{{ $user->username ? '@' . $user->username : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Alamat Email</div>
                                    <div class="fw-semibold text-dark">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Nomor Telepon</div>
                                    <div class="fw-semibold text-dark">{{ $user->phone ?: '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Tanggal Bergabung</div>
                                    <div class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <div class="text-muted small mb-1">Login Terakhir</div>
                                    <div class="fw-semibold text-dark">{{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y, H:i') : 'Sesi saat ini' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Shortcuts -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('profile.edit') }}" class="dashboard-shortcut-card">
                            <div class="dashboard-shortcut-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Pengaturan Profil</div>
                                <div class="small text-muted">Perbarui nama, username, telepon &amp; foto.</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('profile.password') }}" class="dashboard-shortcut-card">
                            <div class="dashboard-shortcut-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Keamanan Kata Sandi</div>
                                <div class="small text-muted">Ganti kata sandi akun Anda secara berkala.</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
