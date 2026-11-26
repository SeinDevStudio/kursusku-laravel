@extends('layouts.admin')

@section('title', 'Admin Dashboard - KursusKu')
@section('page_title', 'Admin Dashboard')

@section('content')
<!-- Statistik Pengguna (Data Aktual Database) -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Total Pengguna</div>
            <div class="admin-stat-value js-counter" data-target="{{ $stats['total_users'] }}">{{ $stats['total_users'] }}</div>
            <div class="admin-stat-sub">Seluruh akun terdaftar</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Pengguna Aktif</div>
            <div class="admin-stat-value text-success js-counter" data-target="{{ $stats['active_users'] }}">{{ $stats['active_users'] }}</div>
            <div class="admin-stat-sub">Diizinkan masuk sistem</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Pengguna Nonaktif</div>
            <div class="admin-stat-value text-secondary js-counter" data-target="{{ $stats['inactive_users'] }}">{{ $stats['inactive_users'] }}</div>
            <div class="admin-stat-sub">Akses dibatasi sementara</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="admin-stat-card admin-stat-accent">
            <div class="admin-stat-label">Total Administrator</div>
            <div class="admin-stat-value text-danger js-counter" data-target="{{ $stats['total_admins'] }}">{{ $stats['total_admins'] }}</div>
            <div class="admin-stat-sub">Hak akses penuh</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pengguna Terbaru -->
    <div class="col-lg-7 col-12">
        <div class="courses-table-card h-100">
            <div class="courses-table-header">
                <div>
                    <h2 class="courses-header-title">Pengguna Terbaru</h2>
                    <p class="courses-header-subtitle">5 akun yang paling baru dibuat di sistem.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-red btn-sm">Kelola Pengguna</a>
            </div>
            <div class="table-responsive">
                <table class="table-courses-clean">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentUsers as $recent)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $recent->name }}</div>
                                    <div class="text-muted small">{{ $recent->email }}</div>
                                </td>
                                <td>
                                    @if ($recent->isAdmin())
                                        <span class="badge-kursusku-red">ADMIN</span>
                                    @else
                                        <span class="badge bg-dark text-white">USER</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($recent->is_active)
                                        <span class="status-pill status-pill-open">Aktif</span>
                                    @else
                                        <span class="status-pill status-pill-closed">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.show', $recent) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aktivitas Login Terakhir -->
    <div class="col-lg-5 col-12">
        <div class="courses-table-card h-100">
            <div class="courses-table-header">
                <div>
                    <h2 class="courses-header-title">Aktivitas Login Terbaru</h2>
                    <p class="courses-header-subtitle">Catatan waktu masuk terakhir pengguna.</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-dark fw-semibold">+ Tambah Akun</a>
            </div>
            <div class="p-3">
                @forelse ($recentLogins as $loginUser)
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                        <div>
                            <div class="fw-semibold text-dark small">{{ $loginUser->name }}</div>
                            <div class="text-muted" style="font-size: 0.78rem;">{{ $loginUser->email }}</div>
                        </div>
                        <div class="text-end small text-secondary">
                            {{ $loginUser->last_login_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">Belum ada riwayat login yang tercatat.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
