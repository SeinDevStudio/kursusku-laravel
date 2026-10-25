<nav class="navbar navbar-expand-lg navbar-kursusku sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            KursusKu<span>.</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#kursuskuNavbar" aria-controls="kursuskuNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="kursuskuNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('enrollments.*') || request()->routeIs('registration') ? 'active' : '' }}" href="{{ route('enrollments.create') }}">Pendaftaran</a>
                </li>
            </ul>
        </div>
    </div>
</nav>