<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Perpustakaan') - Sistem Perpustakaan</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            padding: 2rem 0;
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Main Content --}}
    <main class="py-4">
        <div class="container">
            {{-- Alert Messages --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Global Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Auto-dismiss flash alert messages after 5 seconds (5000 ms)
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        if (bsAlert) {
                            bsAlert.close();
                        }
                    } else {
                        alert.remove();
                    }
                }, 5000);
            });

            // 2. SweetAlert2 Delete Confirmation Dialog
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-delete-confirm');
                if (btn) {
                    e.preventDefault();
                    const form = btn.closest('form');
                    const message = btn.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545', // Bootstrap btn-danger color
                        cancelButtonColor: '#6c757d', // Bootstrap btn-secondary color
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.classList.add('swal-confirm-waiting');

                            // Change button to disabled loading state
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';

                            form.submit();
                        }
                    });
                }
            });

            // 3. Form Submit - Loading State ("Menyimpan...")
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    // Skip if form is for SweetAlert confirmation and isn't confirmed yet
                    if (form.querySelector('.btn-delete-confirm') && !form.classList.contains('swal-confirm-waiting')) {
                        return;
                    }

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        // Avoid double disabling
                        if (submitBtn.disabled) return;

                        // Check if it is a delete form
                        const isDelete = form.querySelector('input[name="_method"][value="DELETE"]');

                        // Save original text in case we need to restore it
                        submitBtn.dataset.originalHtml = submitBtn.innerHTML;

                        // Set spinner loading state
                        submitBtn.disabled = true;
                        if (isDelete) {
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';
                        } else {
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                        }
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>