<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk - KursusKu')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page-body">
    <div class="auth-split-wrapper">
        <!-- Left Asymmetric Editorial Panel (Black Dominant + Red Accent) -->
        <aside class="auth-editorial-panel">
            <div class="auth-editorial-grid" aria-hidden="true"></div>
            <div class="auth-editorial-inner">
                <div class="auth-brand-row">
                    <a href="{{ route('home') }}" class="auth-brand-link">
                        <img src="{{ asset('image/icon kursusku.png') }}" alt="KursusKu Logo" class="navbar-logo-img">
                        <span class="brand-text">KursusKu<span>.</span></span>
                    </a>
                    <a href="{{ route('home') }}" class="auth-back-home">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                        </svg>
                        Beranda
                    </a>
                </div>

                <div class="auth-editorial-copy">
                    <span class="badge-kursusku-red mb-3 d-inline-block">@yield('editorial_badge', 'Portal Pembelajaran Digital')</span>
                    <h1 class="auth-editorial-title">
                        @yield('editorial_title', 'Bangun Keahlian Web Modern Secara Terarah.')
                    </h1>
                    <p class="auth-editorial-desc">
                        @yield('editorial_desc', 'Akses dashboard pembelajaran personal, kelola profil akun Anda, dan lanjutkan penguasaan teknologi web dari dasar hingga produksi.')
                    </p>
                </div>

                <div class="auth-editorial-preview">
                    <div class="auth-preview-thumb">
                        <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Suasana belajar KursusKu">
                    </div>
                    <div class="auth-preview-meta">
                        <div class="auth-preview-label">Kurikulum Terstruktur</div>
                        <div class="auth-preview-sub">HTML, CSS, PHP, MySQL &amp; Laravel</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right Form Panel (Crisp White Contrast) -->
        <main class="auth-form-panel">
            <div class="auth-form-container">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
