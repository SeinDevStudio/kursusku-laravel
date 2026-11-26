@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - KursusKu')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="courses-table-card">
    <div class="courses-table-header">
        <div>
            <h2 class="courses-header-title">Daftar Pengguna</h2>
            <p class="courses-header-subtitle">Kelola akun administrator dan peserta terdaftar.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2 fw-medium rounded-pill">
                {{ $users->total() }} Pengguna
            </span>
            <a href="{{ route('admin.users.create') }}" class="btn-red">
                + Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="p-3 px-4 border-bottom bg-light">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-12">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama, username, atau email...">
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected($role === 'admin')>ADMIN</option>
                    <option value="user" @selected($role === 'user')>USER</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6 col-12">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" @selected($status === 'active')>Aktif</option>
                    <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 col-12 d-flex gap-2">
                <button type="submit" class="btn-red flex-grow-1 justify-content-center">Terapkan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-courses-clean">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No.</th>
                    <th>Pengguna</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $userItem)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ ($users->firstItem() ?? 0) + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $userItem->name }}</div>
                            <div class="text-muted small">{{ $userItem->email }}</div>
                        </td>
                        <td>{{ $userItem->username ? '@' . $userItem->username : '-' }}</td>
                        <td>
                            @if ($userItem->isAdmin())
                                <span class="badge-kursusku-red">ADMIN</span>
                            @else
                                <span class="badge bg-dark text-white">USER</span>
                            @endif
                        </td>
                        <td>
                            @if ($userItem->is_active)
                                <span class="status-pill status-pill-open">Aktif</span>
                            @else
                                <span class="status-pill status-pill-closed">Nonaktif</span>
                            @endif
                        </td>
                        <td class="small text-secondary text-nowrap">
                            {{ $userItem->last_login_at ? $userItem->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}
                        </td>
                        <td class="text-center">
                            <div class="action-buttons-group">
                                <a href="{{ route('admin.users.show', $userItem) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                <a href="{{ route('admin.users.edit', $userItem) }}" class="btn btn-sm btn-outline-warning text-dark">Edit</a>
                                @if ($userItem->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $userItem) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $userItem->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            {{ $userItem->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Tidak ada pengguna yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="p-3 px-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-2 bg-light">
            <div class="text-muted small">
                Menampilkan {{ $users->firstItem() }}&ndash;{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    @endif
</div>
@endsection
