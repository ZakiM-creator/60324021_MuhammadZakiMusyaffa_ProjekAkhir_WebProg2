{{-- 
============================================================
FILE: navbar.blade.php
FUNGSI: Potongan layout (partial) untuk menu navigasi utama (header)
============================================================
--}}
{{-- navbar: Menginisiasi komponen navigasi Bootstrap. navbar-expand-lg: Menu akan terbuka sejajar di layar besar, dan jadi hamburger di layar kecil. navbar-dark bg-primary: Teks warna terang dengan latar warna utama (biru). shadow-sm mb-4: Bayangan kecil di bawah dan margin bawah. --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <!-- Logo / Brand -->
        {{-- navbar-brand: Kelas khusus untuk logo/judul di navbar --}}
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            {{-- Icon buku dengan jarak margin kanan (me-2) dan ukuran font yang lebih besar (fs-4) --}}
            <i class="bi bi-book-fill me-2 fs-4"></i>
            {{-- Fungsi __() digunakan untuk terjemahan (localization). Jika tidak ada terjemahan, tampilkan string aslinya --}}
            <span>{{ __('Perpustakaan') }}</span>
        </a>

        <!-- Hamburger Toggle for Mobile -->
        {{-- Tombol ini (navbar-toggler) hanya muncul di layar kecil (mobile/tablet). Berfungsi untuk membuka/tutup menu (collapse) --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation links and items -->
        {{-- collapse navbar-collapse: Kontainer menu yang bisa disembunyikan/dimunculkan oleh tombol toggle hamburger di atas --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            
            {{-- FORM PENCARIAN GLOBAL --}}
            {{-- d-flex: Display flex. me-auto: Mendorong elemen di sebelahnya ke ujung kanan (push right) --}}
            <form class="d-flex my-2 my-lg-0 ms-lg-3 me-auto" action="{{ route('search') }}" method="GET" style="max-width: 250px;">
                <div class="input-group input-group-sm">
                    {{-- Input pencarian. Value diisi dengan request('q') agar apa yang dicari tetap tampil di kotak setelah halaman dimuat --}}
                    <input class="form-control border-0" type="search" name="q"
                        placeholder="{{ __('Search..') }}" value="{{ request('q') }}">
                    <button class="btn btn-light border-0" type="submit">
                        <i class="bi bi-search text-primary"></i>
                    </button>
                </div>
            </form>

            {{-- MENU UTAMA NAVIGASI --}}
            {{-- navbar-nav: Membungkus item navigasi. gap-1: Jarak antar item. align-items-lg-center: Rata tengah secara vertikal pada layar besar --}}
            <div class="navbar-nav gap-1 py-2 py-lg-0 align-items-lg-center">
                
                {{-- Link Dashboard --}}
                {{-- Fungsi request()->routeIs() atau Request::is() mengecek apakah URL saat ini cocok. Jika cocok, tambahkan kelas 'active fw-bold text-white' agar terlihat terpilih. --}}
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> 
                    {{-- Teks hanya muncul di mobile (d-inline) atau monitor sangat besar (d-xl-inline). Di tablet layar besar akan disembunyikan (d-lg-none) untuk hemat tempat --}}
                    <span class="d-inline d-lg-none d-xl-inline">{{ __('Dashboard') }}</span>
                </a>
                
                {{-- Link Buku --}}
                <a class="nav-link {{ Request::is('buku*') ? 'active fw-bold text-white' : '' }}" href="{{ route('buku.index') }}">
                    <i class="bi bi-book"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Buku') }}</span>
                </a>
                
                {{-- Link Kategori --}}
                <a class="nav-link {{ Request::is('kategori*') ? 'active fw-bold text-white' : '' }}" href="{{ route('kategori.index') }}">
                    <i class="bi bi-tags"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Kategori') }}</span>
                </a>
                
                {{-- Link Anggota --}}
                <a class="nav-link {{ Request::is('anggota*') ? 'active fw-bold text-white' : '' }}" href="{{ route('anggota.index') }}">
                    <i class="bi bi-people"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Anggota') }}</span>
                </a>
                
                {{-- Link Transaksi (aktif di route index, create, dan edit transaksi) --}}
                <a class="nav-link {{ Request::is('transaksi') || Request::is('transaksi/create') || Request::is('transaksi/*/edit') ? 'active fw-bold text-white' : '' }}" href="{{ route('transaksi.index') }}">
                    <i class="bi bi-arrow-left-right"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Transaksi') }}</span>
                </a>
                
                {{-- Link Laporan --}}
                <a class="nav-link {{ Request::is('transaksi/laporan') ? 'active fw-bold text-white' : '' }}" href="{{ route('transaksi.laporan') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> <span class="d-inline d-lg-none d-xl-inline">{{ __('Laporan') }}</span>
                </a>
                
                {{-- Pemisah vertikal (Vertical Rule) berwarna putih transparan (opacity-25) yang hanya tampil di layar desktop (d-none d-lg-block) --}}
                <div class="vr bg-white mx-1 d-none d-lg-block opacity-25"></div>
                
                <!-- Dark Mode Toggle -->
                {{-- Tombol ganti tema. Logika JS-nya ada di bagian bawah file ini. --}}
                <button class="btn btn-sm nav-link" id="theme-toggle" title="{{ __('Toggle Dark/Light Mode') }}">
                    <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                </button>
                
                <!-- Language Switcher -->
                {{-- Dropdown untuk memilih bahasa (ID / EN) --}}
                <div class="dropdown">
                    <button class="btn btn-sm nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{-- app()->getLocale() mengambil bahasa aktif saat ini lalu mengubah jadi huruf kapital (strtoupper) --}}
                        <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    {{-- Daftar opsi bahasa. Mengarah ke route khusus (lang.switch) --}}
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">EN</a></li>
                    </ul>
                </div>

                <!-- Profile Dropdown (Hanya Tampil Jika Login) -->
                {{-- Direktif @auth mengecek apakah user sudah login. Jika belum, blok HTML ini tidak akan di-render ke browser --}}
                @auth
                <div class="dropdown ms-1">
                    {{-- Menampilkan nama depan user saja (menggunakan explode berdasarkan spasi) --}}
                    <button class="btn btn-sm nav-link dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-6"></i>
                        <span class="d-none d-lg-inline">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </button>
                    {{-- Menu dropdown dari avatar profil --}}
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        {{-- Link ke halaman edit profil --}}
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2 text-primary"></i> {{ __('Profile') }}
                            </a>
                        </li>
                        {{-- Garis batas pemisah (divider) --}}
                        <li><hr class="dropdown-divider"></li>
                        {{-- Tombol Logout harus menggunakan form POST untuk keamanan (melindungi dari serangan CSRF) --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                {{-- @csrf wajib ditambahkan ke form POST di Laravel --}}
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

{{-- SCRIPT JAVASCRIPT UNTUK FITUR DARK MODE --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mendapatkan elemen tombol dan icon
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        // Cek tema saat ini dengan membaca attribute 'data-bs-theme' di tag <html>
        // Atribut ini diinisialisasi oleh script pendek di app.blade.php
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        
        // Jika tema gelap, ubah ikon default bulan menjadi ikon matahari
        if(currentTheme === 'dark') {
            themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }

        // Saat tombol toggle diklik
        toggleBtn.addEventListener('click', () => {
            // Cek ulang tema apa yang aktif sekarang
            let activeTheme = document.documentElement.getAttribute('data-bs-theme');
            // Balik nilainya (toggle): gelap jadi terang, terang jadi gelap
            let newTheme = activeTheme === 'dark' ? 'light' : 'dark';
            
            // Set attribute HTML ke tema yang baru (CSS Bootstrap akan merespon ini secara otomatis)
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            
            // Simpan preferensi pengguna di LocalStorage Browser
            // Agar saat halaman di-refresh, tema pilihan tetap bertahan
            localStorage.setItem('theme', newTheme);
            
            // Ubah ikon sesuai tema baru
            if(newTheme === 'dark') {
                themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            } else {
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            }
        });
    });
</script>