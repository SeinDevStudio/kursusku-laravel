@extends('layouts.app')

@section('title', 'Detail Kursus: ' . $course->name . ' - KursusKu')

@section('content')
<!-- Hero Section Compact -->
<section class="courses-hero-compact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge-kursusku-red mb-2 d-inline-block">Detail Informasi</span>
                <h1 class="courses-hero-compact-title">
                    Detail Kursus: <span>{{ $course->name }}</span>
                </h1>
                <p class="courses-hero-compact-desc">
                    Informasi lengkap mengenai kurikulum, kuota peserta, biaya, dan jadwal pelaksanaan kursus.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="courses-content-wrapper">
    <div class="container">
        <div class="create-course-container">
            <!-- Navigation Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="h4 mb-1 fw-bold text-dark">{{ $course->name }}</h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="course-code-pill">{{ $course->code }}</span>
                        @if ($course->status === 'open')
                            <span class="status-pill status-pill-open">Dibuka</span>
                        @elseif ($course->status === 'closed')
                            <span class="status-pill status-pill-closed">Ditutup</span>
                        @else
                            <span class="status-pill status-pill-draft">Draft</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('courses.index') }}" class="btn-secondary-clean py-2 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning text-dark fw-semibold py-2 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" class="me-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus {{ $course->name }} ({{ $course->code }})? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger py-2 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" class="me-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="create-form-card">
                <div class="row g-4">
                    <!-- Deskripsi -->
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small text-uppercase fw-bold letter-spacing d-block mb-1">Deskripsi Materi Kursus</span>
                            <p class="mb-0 text-dark fs-6" style="white-space: pre-line;">
                                {{ $course->description ?: 'Belum ada deskripsi untuk kursus ini.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Spesifikasi Grid -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Kode Kursus</div>
                            <div class="fw-bold fs-5 text-dark">{{ $course->code }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Biaya Investasi Belajar</div>
                            <div class="fw-bold fs-5 text-dark">
                                @if ($course->fee > 0)
                                    Rp {{ number_format($course->fee, 0, ',', '.') }}
                                @else
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Gratis</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Kapasitas Kuota Peserta</div>
                            <div class="fw-bold fs-5 text-dark">{{ $course->quota }} orang</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Tanggal Mulai Pembelajaran</div>
                            <div class="fw-bold fs-5 text-dark">
                                {{ $course->start_date ? $course->start_date->translatedFormat('d F Y') : '-' }}
                                <span class="text-muted fw-normal fs-6">({{ $course->start_date ? $course->start_date->format('d/m/Y') : '-' }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Status Publikasi</div>
                            <div class="mt-1">
                                @if ($course->status === 'open')
                                    <span class="status-pill status-pill-open">Dibuka (Pendaftaran Aktif)</span>
                                @elseif ($course->status === 'closed')
                                    <span class="status-pill status-pill-closed">Ditutup (Pendaftaran Nonaktif)</span>
                                @else
                                    <span class="status-pill status-pill-draft">Draft (Belum Dipublikasikan)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small mb-1">Riwayat Pencatatan Sistem</div>
                            <div class="small text-secondary mt-1">
                                <div>Dibuat: {{ $course->created_at ? $course->created_at->format('d/m/Y H:i') : '-' }}</div>
                                <div>Pembaruan Terakhir: {{ $course->updated_at ? $course->updated_at->format('d/m/Y H:i') : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

