<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            <i class="bi bi-book-fill me-2 fs-4"></i>
            <span>Perpustakaan</span>
        </a>

        <!-- Hamburger Toggle for Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Button Navigation links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto gap-2 py-2 py-lg-0">
                <a class="btn {{ request()->routeIs('dashboard') ? 'btn-light text-primary fw-bold' : 'btn-outline-light' }} d-flex align-items-center" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
                <a class="btn {{ Request::is('buku*') ? 'btn-light text-primary fw-bold' : 'btn-outline-light' }} d-flex align-items-center" href="{{ route('buku.index') }}">
                    <i class="bi bi-book me-1"></i> Buku
                </a>
                <a class="btn {{ Request::is('anggota*') ? 'btn-light text-primary fw-bold' : 'btn-outline-light' }} d-flex align-items-center" href="{{ route('anggota.index') }}">
                    <i class="bi bi-people me-1"></i> Anggota
                </a>
                <a class="btn {{ Request::is('transaksi*') && !Request::is('transaksi/laporan') ? 'btn-light text-primary fw-bold' : 'btn-outline-light' }} d-flex align-items-center" href="{{ route('transaksi.index') }}">
                    <i class="bi bi-arrow-left-right me-1"></i> Transaksi
                </a>
                <a class="btn {{ Request::is('transaksi/laporan') ? 'btn-light text-primary fw-bold' : 'btn-outline-light' }} d-flex align-items-center" href="{{ route('transaksi.laporan') }}">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan
                </a>
            </div>
        </div>
    </div>
</nav>