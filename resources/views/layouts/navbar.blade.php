<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            <i class="bi bi-book-fill me-2 fs-4"></i>
            <span>{{ __('Perpustakaan') }}</span>
        </a>

        <!-- Hamburger Toggle for Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Button Navigation links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex my-2 my-lg-0 ms-lg-3 me-auto" action="{{ route('search') }}" method="GET" style="max-width: 250px;">
                <div class="input-group input-group-sm">
                    <input class="form-control border-0" type="search" name="q"
                        placeholder="{{ __('Search..') }}" value="{{ request('q') }}">
                    <button class="btn btn-light border-0" type="submit">
                        <i class="bi bi-search text-primary"></i>
                    </button>
                </div>
            </form>

            <div class="navbar-nav gap-1 py-2 py-lg-0 align-items-lg-center">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Dashboard') }}</span>
                </a>
                <a class="nav-link {{ Request::is('buku*') ? 'active fw-bold text-white' : '' }}" href="{{ route('buku.index') }}">
                    <i class="bi bi-book"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Buku') }}</span>
                </a>
                <a class="nav-link {{ Request::is('kategori*') ? 'active fw-bold text-white' : '' }}" href="{{ route('kategori.index') }}">
                    <i class="bi bi-tags"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Kategori') }}</span>
                </a>
                <a class="nav-link {{ Request::is('anggota*') ? 'active fw-bold text-white' : '' }}" href="{{ route('anggota.index') }}">
                    <i class="bi bi-people"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Anggota') }}</span>
                </a>
                <a class="nav-link {{ Request::is('transaksi') || Request::is('transaksi/create') || Request::is('transaksi/*/edit') ? 'active fw-bold text-white' : '' }}" href="{{ route('transaksi.index') }}">
                    <i class="bi bi-arrow-left-right"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Transaksi') }}</span>
                </a>
                <a class="nav-link {{ Request::is('transaksi/laporan') ? 'active fw-bold text-white' : '' }}" href="{{ route('transaksi.laporan') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Laporan') }}</span>
                </a>
                
                <div class="vr bg-white mx-1 d-none d-lg-block opacity-25"></div>
                
                <!-- Dark Mode Toggle -->
                <button class="btn btn-sm nav-link" id="theme-toggle" title="{{ __('Toggle Dark/Light Mode') }}">
                    <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                </button>
                
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-sm nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">EN</a></li>
                    </ul>
                </div>

                <!-- Profile Dropdown -->
                @auth
                <div class="dropdown ms-1">
                    <button class="btn btn-sm nav-link dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-6"></i>
                        <span class="d-none d-lg-inline">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2 text-primary"></i> {{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        // Cek current theme dari localStorage atau attribute html
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        if(currentTheme === 'dark') {
            themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }

        toggleBtn.addEventListener('click', () => {
            let activeTheme = document.documentElement.getAttribute('data-bs-theme');
            let newTheme = activeTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            if(newTheme === 'dark') {
                themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            } else {
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            }
        });
    });
</script>