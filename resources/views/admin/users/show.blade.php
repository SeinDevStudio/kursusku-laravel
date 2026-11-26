@extends('layouts.admin')

@section('title', 'Detail Pengguna - KursusKu')
@section('page_title', 'Detail Akun Pengguna')

@section('content')
<div class="courses-table-card" style="max-width: 820px;">
    <div class="courses-table-header">
        <div class="d-flex align-items-center gap-3">
            @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Foto {{ $user->name }}" class="rounded-circle object-fit-cover" style="width: 54px; height: 54px;">
            @else
                <div class="dashboard-avatar-fallback" style="width: 54px; height: 54px; font-size: 1.1rem;">{{ $user->initials }}</div>
            @endif
            <div>
                <h2 class="courses-header-title mb-0">{{ $user->name }}</h2>
                <div class="text-muted small">{{ $user->email }}</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary-clean btn-sm">Kembali</a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning text-dark fw-semibold">Edit</a>
        </div>
    </div>

    <div class="p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Username</div>
                    <div class="fw-semibold text-dark">{{ $user->username ? '@' . $user->username : '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Nomor Telepon</div>
                    <div class="fw-semibold text-dark">{{ $user->phone ?: '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Role Akun</div>
                    <div>
                        @if ($user->isAdmin())
                            <span class="badge-kursusku-red">ADMIN</span>
                        @else
                            <span class="badge bg-dark text-white">USER</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Status Akun</div>
                    <div>
                        @if ($user->is_active)
                            <span class="status-pill status-pill-open">Aktif</span>
                        @else
                            <span class="status-pill status-pill-closed">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Terdaftar Sejak</div>
                    <div class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="text-muted small mb-1">Login Terakhir</div>
                    <div class="fw-semibold text-dark">{{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y, H:i') : 'Belum pernah login' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
