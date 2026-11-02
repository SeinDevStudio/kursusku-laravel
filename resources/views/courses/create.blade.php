@extends('layouts.app')

@section('title', 'Tambah Kursus Baru - KursusKu')

@section('content')
<div class="container py-5">
    <div class="create-course-container">
        <!-- Header & Breadcrumb Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">Tambah Kursus Baru</h1>
                <p class="text-muted small mb-0">Isi formulir berikut untuk mendaftarkan kursus baru ke dalam sistem KursusKu.</p>
            </div>
            <a href="{{ route('courses.index') }}" class="btn-secondary-clean py-2 px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Alert Error Summary -->
        @if ($errors->any())
            <div class="alert-kursusku-danger mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-danger flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <strong>Terdapat Kesalahan Pengisian Data:</strong>
                </div>
                <ul class="mb-0 ps-4 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Create Form Card -->
        <div class="create-form-card">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                <!-- Kode & Nama Kursus -->
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="code" class="form-label">
                            Kode Kursus <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}"
                            placeholder="Contoh: WEB-01"
                            maxlength="20"
                            required
                        >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="form-helper-text">Maksimal 20 karakter, unik.</span>
                    </div>

                    <div class="col-md-7 mb-3">
                        <label for="name" class="form-label">
                            Nama Kursus <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Pemrograman Web Laravel"
                            maxlength="120"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="form-helper-text">Maksimal 120 karakter.</span>
                    </div>
                </div>

                <!-- Deskripsi Kursus -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Kursus</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="3"
                        placeholder="Jelaskan ringkasan materi dan kompetensi yang akan dipelajari peserta..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Biaya & Kuota -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fee" class="form-label">
                            Biaya Kursus (Rp) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="fee"
                            name="fee"
                            class="form-control @error('fee') is-invalid @enderror"
                            value="{{ old('fee', 0) }}"
                            min="0"
                            step="1"
                            required
                        >
                        @error('fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="form-helper-text">Isi 0 jika kursus gratis.</span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="quota" class="form-label">
                            Kuota Peserta <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="quota"
                            name="quota"
                            class="form-control @error('quota') is-invalid @enderror"
                            value="{{ old('quota', 20) }}"
                            min="1"
                            max="500"
                            step="1"
                            required
                        >
                        @error('quota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <span class="form-helper-text">Batas kuota 1 s.d. 500 orang (default: 20).</span>
                    </div>
                </div>

                <!-- Tanggal Mulai & Status -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">
                            Tanggal Mulai <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date') }}"
                            required
                        >
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label">
                            Status Kursus <span class="text-danger">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft (Belum Dibuka)</option>
                            <option value="open" @selected(old('status') === 'open')>Dibuka (Pendaftaran Aktif)</option>
                            <option value="closed" @selected(old('status') === 'closed')>Ditutup (Pendaftaran Berakhir)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 pt-3 border-top">
                    <button type="submit" class="btn-red">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Simpan Kursus
                    </button>
                    <a href="{{ route('courses.index') }}" class="btn-secondary-clean">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
