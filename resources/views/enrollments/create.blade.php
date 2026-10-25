@extends('layouts.app')

@section('title', 'Pendaftaran Kursus - KursusKu')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 col-12">
                <div class="section-title-wrap text-center mb-4">
                    <span class="hero-label">Formulir Pendaftaran</span>
                    <h1 class="section-title d-block mx-auto">Pendaftaran Kursus</h1>
                    <p class="section-subtitle">Pilih kursus yang ingin Anda ikuti dan amankan kursi Anda.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <strong>Perhatian:</strong> Terdapat kesalahan pengisian data pada formulir.
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card form-card shadow-sm border-0">
                    <form method="POST" action="{{ route('enrollments.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Contoh: Muhammad Husein"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="nama@domain.com"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course_id" class="form-label fw-semibold">Pilihan Kursus <span class="text-danger">*</span></label>
                            <select name="course_id"
                                    id="course_id"
                                    class="form-select @error('course_id') is-invalid @enderror"
                                    data-availability-template="{{ url('/courses/__COURSE__/availability') }}"
                                    required>
                                <option value="">-- Pilih Kursus --</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c['code'] }}" @selected(old('course_id') == $c['code'])>
                                        {{ $c['code'] }} — {{ $c['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Area Availability Ajax -->
                        <div id="availability" class="alert alert-secondary my-3" aria-live="polite">
                            Pilih kursus untuk melihat sisa kursi melalui Ajax.
                        </div>

                        <button type="submit" class="btn-red w-100 py-2 fs-6 mt-2">Daftar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection