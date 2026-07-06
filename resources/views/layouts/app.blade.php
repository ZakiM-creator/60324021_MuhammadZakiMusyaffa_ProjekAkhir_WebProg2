<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @if($theme === 'bootstrap')
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
            <!-- Animate.css for Advanced Animations -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
            <!-- Fix CSS Collision & Custom CSS -->
            <style>
                .navbar-collapse.collapse { visibility: visible !important; }
                
                /* Transition Effects & Micro-interactions */
                .btn, .card { transition: all 0.3s ease; }
                .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
                
                /* Touch-friendly buttons (min 44x44px) */
                .btn-icon { width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; }
                
                /* Dark mode specific overrides */
                [data-bs-theme="dark"] body { background-color: #121212 !important; }
                [data-bs-theme="dark"] .bg-gray-100 { background-color: #121212 !important; }
                [data-bs-theme="dark"] .card { background-color: #1e1e1e; border-color: #333; }
                [data-bs-theme="dark"] .card-header { border-bottom-color: #333; }
                
                /* Loading Animation */
                #page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease; }
                [data-bs-theme="dark"] #page-loader { background: rgba(18,18,18,0.8); }
                .spinner { width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #0d6efd; border-radius: 50%; animation: spin 1s linear infinite; }
                @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            </style>
            <!-- Dark Mode Init Script (To prevent FOUC) -->
            <script>
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', theme);
            </script>
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        @yield('styles')
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        @if($theme === 'bootstrap')
            <!-- Page Loader -->
            <div id="page-loader">
                <div class="spinner"></div>
            </div>
        @endif

        <div class="min-h-screen bg-gray-100">
            @if($theme === 'bootstrap')
                @include('layouts.navbar')

                <div class="container my-5 animate__animated animate__fadeIn">
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>

                @include('layouts.footer')
            @else
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            @endif
        </div>

        @if($theme === 'bootstrap')
            <!-- Bootstrap JS Bundle -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @endif
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Global Delete Confirmation Handler -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.body.addEventListener('click', function (e) {
                    const button = e.target.closest('.btn-delete, .btn-delete-confirm');
                    if (button) {
                        e.preventDefault();
                        const form = button.closest('form');
                        if (!form) return;
                        
                        const judul = button.getAttribute('data-judul');
                        const confirmMsg = button.getAttribute('data-confirm');
                        let message = 'Apakah Anda yakin ingin menghapus data ini?';
                        
                        if (confirmMsg) {
                            message = confirmMsg;
                        } else if (judul) {
                            message = `Apakah Anda yakin ingin menghapus "${judul}"?`;
                        }

                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                });

                // Hide loader after page load
                window.addEventListener('load', function() {
                    const loader = document.getElementById('page-loader');
                    if(loader) {
                        loader.style.opacity = '0';
                        setTimeout(() => loader.style.display = 'none', 500);
                    }
                });
            });
        </script>
        @yield('scripts')
        @stack('scripts')
    </body>
</html>
