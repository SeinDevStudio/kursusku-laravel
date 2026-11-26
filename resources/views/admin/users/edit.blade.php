@extends('layouts.admin')

@section('title', 'Edit Pengguna - KursusKu')
@section('page_title', 'Edit Pengguna: ' . $user->name)

@section('content')
<div class="create-form-card" style="max-width: 760px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="form-control @error('username') is-invalid @enderror">
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="role" class="form-label fw-semibold">Role Akun <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="user" @selected(old('role', $user->role) === 'user')>USER</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>ADMIN</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="is_active" class="form-label fw-semibold">Status Akun <span class="text-danger">*</span></label>
                <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                    <option value="1" @selected(old('is_active', $user->is_active ? '1' : '0') == '1')>Aktif</option>
                    <option value="0" @selected(old('is_active', $user->is_active ? '1' : '0') == '0')>Nonaktif</option>
                </select>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label fw-semibold">Kata Sandi Baru (Opsional)</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary-clean">Batal</a>
            <button type="submit" class="btn-red">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
