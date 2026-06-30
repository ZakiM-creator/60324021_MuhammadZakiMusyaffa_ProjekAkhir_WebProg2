<x-app-layout theme="bootstrap" title="Dashboard Perpustakaan">

    {{-- HEADER DASHBOARD --}}
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-2"> Dashboard Perpustakaan</h1>
            <p class="text-muted">Ringkasan statistik dan data terbaru sistem perpustakaan</p>
        </div>
    </div>

    {{-- STATISTIK BUKU --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-uppercase text-secondary fw-semibold mb-3" style="letter-spacing: 0.05em;">Statistik Buku</h5>
        </div>

        {{-- Card Total Buku --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Buku</h6>
                            <h2 class="fw-bold mb-0">{{ $totalBuku }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Buku Tersedia --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Buku Tersedia</h6>
                            <h2 class="fw-bold mb-0 text-success">{{ $bukuTersedia }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Buku Habis --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Buku Habis</h6>
                            <h2 class="fw-bold mb-0 text-danger">{{ $bukuHabis }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK ANGGOTA --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-uppercase text-secondary fw-semibold mb-3" style="letter-spacing: 0.05em;">Statistik Anggota</h5>
        </div>

        {{-- Card Total Anggota --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Anggota</h6>
                            <h2 class="fw-bold mb-0">{{ $totalAnggota }}</h2>
                            <small class="text-muted">Semua member</small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-info" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Anggota Aktif --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Anggota Aktif</h6>
                            <h2 class="fw-bold mb-0 text-success">{{ $anggotaAktif }}</h2>
                            <small class="text-muted">Status aktif</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Anggota Nonaktif --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Anggota Nonaktif</h6>
                            <h2 class="fw-bold mb-0 text-secondary">{{ $anggotaNonaktif }}</h2>
                            <small class="text-muted">Status nonaktif</small>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-secondary" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256ZM12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI KETERLAMBATAN --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-uppercase text-danger fw-semibold mb-3" style="letter-spacing: 0.05em;">Peringatan Keterlambatan</h5>
        </div>

        {{-- Card Buku Terlambat --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Buku Terlambat</h6>
                            <h2 class="fw-bold mb-0 text-danger">{{ $totalTerlambat }}</h2>
                            <small class="text-muted">Transaksi dipinjam > 7 hari</small>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List Anggota Terlambat --}}
        <div class="col-md-8 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-danger">Daftar Anggota Terlambat</h5>
                    <span class="badge bg-danger">{{ $totalTerlambat }} Orang</span>
                </div>
                <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
                    @if($transaksiTerlambat->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Tidak ada anggota yang terlambat mengembalikan buku</p>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($transaksiTerlambat as $transaksi)
                        @php
                            $keterlambatan = $transaksi->tanggal_kembali->diffInDays(now()->startOfDay());
                        @endphp
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $transaksi->anggota->nama ?? '-' }}</h6>
                                    <small class="text-muted">
                                        Buku: <strong>{{ $transaksi->buku->judul ?? '-' }}</strong><br>
                                        Tgl Pinjam: {{ $transaksi->tanggal_pinjam->format('d/m/Y') }}
                                        (Tenggat: {{ $transaksi->tanggal_kembali->format('d/m/Y') }})
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger mb-1">Terlambat {{ $keterlambatan }} hari</span>
                                    <br>
                                    <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 small">Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- BUKU & ANGGOTA TERBARU --}}
    <div class="row mb-4">
        {{-- BUKU TERBARU --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"> 5 Buku Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @if($bukuTerbaru->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Belum ada data buku</p>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($bukuTerbaru as $buku)
                        <a href="{{ route('buku.show', $buku->id) }}"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $buku->judul }}</h6>
                                    <small class="text-muted">
                                        {{ $buku->pengarang }} • {{ $buku->tahun_terbit }}
                                    </small>
                                </div>
                                <div class="ms-2">
                                    {!! $buku->status_stok_badge !!}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-top text-center">
                    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Buku →
                    </a>
                </div>
            </div>
        </div>

        {{-- ANGGOTA TERBARU --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"> 5 Anggota Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @if($anggotaTerbaru->isEmpty())
                    <p class="text-muted text-center py-4 mb-0">Belum ada data anggota</p>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($anggotaTerbaru as $anggota)
                        <a href="{{ route('anggota.show', $anggota->id) }}"
                            class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $anggota->nama }}</h6>
                                    <small class="text-muted">
                                        {{ $anggota->email }} •
                                        Daftar: {{ $anggota->tanggal_daftar->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="ms-2">
                                    {!! $anggota->status_badge !!}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-top text-center">
                    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-info">
                        Lihat Semua Anggota →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK LINKS --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"> Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('buku.index') }}"
                                class="btn btn-outline-primary w-100 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                                </svg>
                                Kelola Buku
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('anggota.index') }}"
                                class="btn btn-outline-info w-100 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                </svg>
                                Kelola Anggota
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('buku.create') }}"
                                class="btn btn-outline-success w-100 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                </svg>
                                Tambah Buku
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('anggota.create') }}"
                                class="btn btn-outline-warning w-100 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                </svg>
                                Tambah Anggota
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>