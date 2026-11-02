<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KursusKu - Platform Edukasi Web')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.navbar')

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="footer-kursusku-pro">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6 text-center text-md-start">
                    <div class="footer-brand-title">KursusKu<span>.</span></div>
                    <p class="mb-0 text-muted small">Platform pembelajaran web bertahap untuk mahasiswa dan pengembang pemula.</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-nav-links justify-content-center justify-content-md-end">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('courses.index') }}">Daftar Kursus</a></li>
                        <li><a href="{{ route('enrollments.create') }}">Pendaftaran</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-25 mt-4 pt-3 text-center text-muted small">
                &copy; {{ date('Y') }} KursusKu. Praktikum Pemrograman Web III. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
