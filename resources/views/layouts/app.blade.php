{{-- 
============================================================
FILE: app.blade.php
FUNGSI: Master layout utama untuk aplikasi (Kerangka Utama HTML)
============================================================
--}}
<!DOCTYPE html>
{{-- Tag html utama dengan pengaturan bahasa dinamis dari fungsi app()->getLocale() --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Set karakter ke UTF-8 (standar modern) --}}
        <meta charset="utf-8">
        {{-- Viewport untuk memastikan website tampil baik (responsif) di layar HP/Tablet --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- Token CSRF (Cross-Site Request Forgery) untuk otentikasi form submission dan request AJAX agar aman dari serangan --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Tag Title. Akan mengambil variabel $title (jika ada), jika tidak, gunakan nama aplikasi default --}}
        <title>{{ $title ?? '' ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- Teknik preconnect mempercepat pengambilan aset dari domain lain --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Font Figtree sebagai font utama aplikasi --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- ============================================================== --}}
        {{-- LOGIKA TEMA (BOOTSTRAP vs BREEZE/TAILWIND)                       --}}
        {{-- Jika variabel $theme bernilai 'bootstrap', gunakan desain kustom --}}
        {{-- Jika tidak (default Laravel), gunakan Tailwind CSS (navigasi bawaan) --}}
        {{-- ============================================================== --}}
        @if(isset($theme) && $theme === 'bootstrap')
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons (Untuk icon buku, search, profil, dll) -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
            <!-- Animate.css (Library CSS untuk membuat efek animasi masuk yang halus) -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
            
            <!-- Custom CSS Styling Internal -->
            <style>
                /* Mencegah bentrok CSS Bootstrap dengan Tailwind (jika ada sisa) */
                .navbar-collapse.collapse { visibility: visible !important; }
                
                /* Micro-interactions: Transisi halus 0.3 detik pada tombol dan kotak kartu */
                .btn, .card { transition: all 0.3s ease; }
                /* Efek melayang (hover) saat kursor diarahkan ke elemen card (memberi dimensi kedalaman) */
                .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
                
                /* Responsive UI: Menjadikan tombol cukup besar agar mudah ditekan di layar HP */
                .btn-icon { width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; }
                
                /* Kustomisasi gaya saat mode Gelap (Dark Mode) aktif */
                /* data-bs-theme="dark" adalah atribut HTML standar Bootstrap 5.3+ */
                [data-bs-theme="dark"] body { background-color: #121212 !important; }
                [data-bs-theme="dark"] .bg-gray-100 { background-color: #121212 !important; }
                [data-bs-theme="dark"] .card { background-color: #1e1e1e; border-color: #333; }
                [data-bs-theme="dark"] .card-header { border-bottom-color: #333; }
                
                /* Gaya untuk Loading Animation Layout (Muncul sesaat sebelum halaman dimuat) */
                #page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease; }
                [data-bs-theme="dark"] #page-loader { background: rgba(18,18,18,0.8); }
                /* Efek cincin berputar untuk visualisasi proses memuat data */
                .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #0d6efd; border-radius: 50%; animation: spin 1s linear infinite; }
                @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            </style>
            
            <!-- Dark Mode Init Script -->
            <!-- Script diletakkan di HEAD agar dieksekusi duluan. Mencegah layar berkedip putih ke gelap (FOUC) saat halaman dimuat. -->
            <script>
                // Baca penyimpanan browser (localStorage), cari key 'theme'. Jika kosong jadikan 'light' default
                const theme = localStorage.getItem('theme') || 'light';
                // Terapkan attribute pada tag <html>
                document.documentElement.setAttribute('data-bs-theme', theme);
            </script>
        @endif

        <!-- Scripts utama bawaan laravel Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 CSS (Library untuk pop-up/alert box interaktif pengganti fungsi alert() bawaan browser) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        
        {{-- Blade Directives untuk menyisipkan CSS spesifik halaman jika halaman anak mendefinisikannya --}}
        @yield('styles')
        @stack('styles')
    </head>
    
    {{-- Antialiased: membuat teks font terlihat lebih halus (smooth) pada sistem operasi tertentu --}}
    <body class="font-sans antialiased">
        
        @if(isset($theme) && $theme === 'bootstrap')
            <!-- Komponen HTML untuk Loading (hanya tampil sesaat setelah body diload) -->
            <div id="page-loader">
                <div class="spinner"></div>
            </div>
        @endif

        {{-- Latar belakang keseluruhan halaman. Tinggi minimum diatur setinggi viewport layar (min-h-screen) --}}
        <div class="min-h-screen bg-gray-100">
            @if(isset($theme) && $theme === 'bootstrap')
                
                {{-- Menyisipkan (include) header menu navigasi atas (Bootstrap) --}}
                @include('layouts.navbar')

                {{-- Kontainer utama tempat isi konten berada. animate__fadeIn membuat halaman seolah memudar masuk saat dibuka --}}
                <div class="container my-5 animate__animated animate__fadeIn">
                    
                    {{-- Flash Messages: Menangkap pesan status/error dari Session Controller (misal: "Data berhasil disimpan!") --}}
                    @if (session('success'))
                        {{-- Alert Bootstrap warna hijau (success), dengan tombol silang (dismissible) --}}
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        {{-- Alert Bootstrap warna merah (danger) jika terjadi kesalahan/gagal proses --}}
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- $slot adalah variabel spesial tempat isi konten/HTML dari view anak dimasukkan --}}
                    {{ $slot }}
                </div>

                {{-- Menyisipkan (include) bagian kaki halaman (footer) --}}
                @include('layouts.footer')
                
            @else
                {{-- Jika Tema Bukan Bootstrap (artinya UI default dari Laravel Breeze/Tailwind) --}}
                @include('layouts.navigation')

                <!-- Page Heading (Bagian Judul Halaman Atas) -->
                {{-- Hanya dirender jika view anak mengirimkan (slot) header --}}
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content (Konten Utama untuk Tailwind mode) -->
                <main>
                    {{ $slot }}
                </main>
            @endif
        </div>

        @if(isset($theme) && $theme === 'bootstrap')
            <!-- File JavaScript Inti dari Bootstrap agar interaksi seperti menu dropdown/modal bisa berjalan -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @endif
        
        <!-- File JavaScript Inti dari SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Global Delete Confirmation Handler -->
        {{-- Script JavaScript di bawah ini berfungsi memotong aksi tombol hapus, dan menampilkan konfirmasi dialog SweetAlert2 terlebih dahulu sebelum form Submit dijalankan --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Mendaftarkan event Listener ke tingkat tertinggi (Body) untuk teknik Event Delegation
                document.body.addEventListener('click', function (e) {
                    
                    // Mengecek apakah elemen yang di-klik user (atau induknya) memiliki kelas btn-delete
                    const button = e.target.closest('.btn-delete, .btn-delete-confirm');
                    
                    if (button) {
                        e.preventDefault(); // Menghentikan form disubmit dengan segera secara otomatis
                        
                        const form = button.closest('form'); // Mencari form form-delete terdekat
                        if (!form) return;
                        
                        // Membaca data atribut kustom HTML (contoh: data-judul="Laskar Pelangi") dari tombol
                        const judul = button.getAttribute('data-judul');
                        const confirmMsg = button.getAttribute('data-confirm');
                        let message = 'Apakah Anda yakin ingin menghapus data ini?';
                        
                        // Menentukan teks peringatan dinamis berdasarkan atribut yang dikirim tombol
                        if (confirmMsg) {
                            message = confirmMsg;
                        } else if (judul) {
                            message = `Apakah Anda yakin ingin menghapus "${judul}"?`;
                        }

                        // Memanggil pop-up/dialog SweetAlert2
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: message, // Pesan dinamis
                            icon: 'warning', // Icon tanda seru kuning
                            showCancelButton: true,
                            confirmButtonColor: '#d33', // Warna merah untuk tombol hapus
                            cancelButtonColor: '#3085d6', // Warna biru untuk batal
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            // Jika user mengklik "Ya", barulah Form Submit dijalankan paksa lewat JavaScript (trigger route Destroy)
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });

                // Script menghilangkan visual animasi loader setelah seluruh halaman & aset (gambar/css) selesai didownload
                window.addEventListener('load', function() {
                    const loader = document.getElementById('page-loader');
                    if(loader) {
                        // Membuatnya perlahan menghilang (efek pudar opacity lewat CSS)
                        loader.style.opacity = '0';
                        // Setelah setengah detik (500ms, agar fade out selesai berjalan), baru buang total dari layout flow
                        setTimeout(() => loader.style.display = 'none', 500);
                    }
                });
            });
        </script>
        
        {{-- Tempat injeksi kode JavaScript tambahan khusus (jika halaman anak membutuhkannya lewat directive @push('scripts') ) --}}
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
