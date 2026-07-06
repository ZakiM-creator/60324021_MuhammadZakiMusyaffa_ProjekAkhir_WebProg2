<x-app-layout theme="bootstrap" title="Dashboard">
<div class="container-fluid py-4">
    <h2 class="mb-4">Dashboard Perpustakaan</h2>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        @foreach([
        ['Total Buku', $stats['total_buku'], 'bi-book', 'primary'],
        ['Anggota Aktif', $stats['total_anggota'], 'bi-people', 'success'],
        ['Sedang Dipinjam', $stats['sedang_dipinjam'], 'bi-journal-arrow-up', 'info'],
        ['Terlambat', $stats['terlambat'], 'bi-exclamation-triangle', 'danger'],
        ['Transaksi Hari Ini', $stats['transaksi_hari_ini'], 'bi-calendar-check', 'warning'],
        ['Buku Tersedia', $stats['buku_tersedia'], 'bi-bookshelf', 'secondary'],
        ['Total Transaksi', $stats['total_transaksi'], 'bi-receipt', 'dark'],
        ['Denda Bulan Ini', 'Rp ' . number_format($stats['denda_bulan_ini'], 0, ',', '.'), 'bi-cash', 'danger'],
        ] as [$label, $value, $icon, $color])
        <div class="col-xl-3 col-md-6">
            <div class="card border-{{ $color }} h-100">
                <div class="card-body d-flex align-items-center">
                    <i class="bi {{ $icon }} fs-1 text-{{ $color }} me-3"></i>
                    <div>
                        <h6 class="text-muted mb-1">{{ $label }}</h6>
                        <h4 class="mb-0">{{ $value }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts Row 1 --}}
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white fw-bold"><i class="bi bi-graph-up text-primary me-2"></i> Trend Peminjaman (6 Bulan)</div>
                <div class="card-body">
                    <canvas id="chartTransaksi" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white fw-bold"><i class="bi bi-pie-chart text-info me-2"></i> Status Transaksi</div>
                <div class="card-body">
                    <canvas id="chartStatus" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white fw-bold"><i class="bi bi-bar-chart text-success me-2"></i> Top 10 Buku Terpopuler</div>
                <div class="card-body">
                    <canvas id="chartBuku" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white fw-bold"><i class="bi bi-pie-chart-fill text-warning me-2"></i> Kategori Buku</div>
                <div class="card-body">
                    <canvas id="chartKategori" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent & Late Transactions Row --}}
    <div class="row">
        {{-- Recent Transactions --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">Transaksi Terbaru</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransaksi as $trx)
                            <tr>
                                <td>{{ $trx->kode_transaksi }}</td>
                                <td>{{ $trx->anggota->nama }}</td>
                                <td>{{ $trx->buku->judul }}</td>
                                <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Buku Terlambat Widget --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-danger">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle me-1"></i> Buku Terlambat</span>
                    <span class="badge bg-white text-danger rounded-pill">{{ $bukuTerlambat->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($bukuTerlambat as $trx)
                        @php
                            $hari = $trx->tanggal_kembali->diffInDays(now()->startOfDay());
                        @endphp
                        <a href="{{ route('transaksi.show', $trx->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $trx->anggota->nama }}</div>
                                <small class="text-muted">{{ $trx->buku->judul }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">Terlambat {{ $hari }} hari</span>
                        </a>
                        @empty
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-check-circle text-success fs-3 d-block mb-2"></i>
                            Tidak ada buku yang terlambat
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('transaksi.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle me-1"></i>
                                Proses Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('buku.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-book me-1"></i>
                                Tambah Buku
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('anggota.create') }}" class="btn btn-outline-warning text-dark w-100 py-3">
                                <i class="bi bi-person-plus me-1"></i>
                                Tambah Anggota
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('transaksi.laporan') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-file-earmark-bar-graph me-1"></i>
                                Laporan Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Line chart — Trend Peminjaman
    new Chart(document.getElementById('chartTransaksi'), {
        type: 'line',
        data: {
            labels: @json($chartData->pluck('bulan')),
            datasets: [{
                    label: 'Peminjaman',
                    data: @json($chartData->pluck('pinjam')),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Pengembalian',
                    data: @json($chartData->pluck('kembali')),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } } }
    });

    // 2. Bar chart — Top 10 Buku Populer
    new Chart(document.getElementById('chartBuku'), {
        type: 'bar',
        data: {
            labels: @json($bukuPopuler->pluck('judul')),
            datasets: [{
                label: 'Jumlah Dipinjam',
                data: @json($bukuPopuler->pluck('transaksis_count')),
                backgroundColor: '#198754',
                borderRadius: 4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // 3. Pie chart — Kategori Buku
    new Chart(document.getElementById('chartKategori'), {
        type: 'pie',
        data: {
            labels: @json($kategoriBuku->pluck('nama_kategori')),
            datasets: [{
                data: @json($kategoriBuku->pluck('bukus_count')),
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#0dcaf0']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // 4. Donut chart — Status Transaksi
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: @json($statusTransaksi->pluck('status')),
            datasets: [{
                data: @json($statusTransaksi->pluck('count')),
                backgroundColor: ['#ffc107', '#198754', '#dc3545', '#6c757d']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
    });
</script>
@endpush
</x-app-layout>