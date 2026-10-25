<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - Belajar Web Secara Bertahap</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-wrapper">
            <a href="{{ route('home') }}" class="brand">
                {{ $siteName }}<span>.</span>
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-link active">Beranda</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container hero-wrapper">
                <div class="hero-content">
                    <h1>Belajar Web <span>Secara Bertahap</span></h1>
                    <p>Mulai dari pemahaman dasar HTML, CSS, logika PHP native hingga arsitektur modern menggunakan Laravel framework.</p>
                </div>
                <div class="hero-image-box">
                    <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Hero KursusKu" class="hero-image">
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section class="courses-section">
            <div class="container">
                <div class="section-header">
                    <h2>Kursus Pilihan</h2>
                    <p>Pilihan materi praktis yang disusun terstruktur dari dasar hingga mahir.</p>
                </div>

                <div class="course-grid">
                    @foreach ($featured as $course)
                        <article class="card">
                            <span class="course-badge">{{ $course['code'] }}</span>
                            <h3 class="course-name">{{ $course['name'] }}</h3>
                            <p class="course-desc">{{ $course['description'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. Praktikum Pemrograman Web III.</p>
        </div>
    </footer>
</body>
</html>