<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KursusKu - Platform Belajar Web')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="footer-kursusku">
        <div class="container text-center">
            <div class="footer-brand">KursusKu<span>.</span></div>
            <p class="mb-1">Belajar web secara bertahap.</p>
            <p class="text-secondary small">&copy; {{ date('Y') }} KursusKu. Praktikum Pemrograman Web III.</p>
        </div>
    </footer>
</body>
</html>