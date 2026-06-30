<x-app-layout theme="bootstrap" title="Laporan Transaksi">
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-funnel me-1"></i> Filter Laporan Transaksi
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('transaksi.laporan') }}" method="GET">
                    <div class="row g-3">
                        {{-- Range Tanggal --}}
                        <div class="col-md-3">
                            <label for="tgl_mulai" class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" 
                                   value="{{ request('tgl_mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_selesai" class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" 
                                   value="{{ request('tgl_selesai') }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-bold">Status Peminjaman</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Semua" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        {{-- Anggota --}}
                        <div class="col-md-3">
                            <label for="anggota_id" class="form-label fw-bold">Anggota</label>
                            <select name="anggota_id" id="anggota_id" class="form-select">
                                <option value="">Semua Anggota</option>
                                @foreach($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('transaksi.laporan') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Content Area for PDF Export --}}
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-end mb-3">
            <button id="btn-pdf-export" class="btn btn-danger">
                <i class="bi bi-file-pdf me-1"></i> Export PDF
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div id="laporan-cetak" class="card-body p-4 bg-white">
                {{-- Kop Laporan --}}
                <div class="text-center mb-4 pb-3 border-bottom">
                    <h2 class="fw-bold mb-1">LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
                    <h5 class="text-muted mb-2">Sistem Manajemen Perpustakaan Laravel</h5>
                    <p class="mb-0 small text-muted">
                        Dicetak pada: {{ date('d F Y H:i') }}
                        @if(request('tgl_mulai') || request('tgl_selesai') || (request('status') && request('status') !== 'Semua') || request('anggota_id'))
                            <br>
                            <strong>Filter Aktif:</strong> 
                            @if(request('tgl_mulai')) Dari: {{ date('d/m/Y', strtotime(request('tgl_mulai'))) }} @endif
                            @if(request('tgl_selesai')) Sampai: {{ date('d/m/Y', strtotime(request('tgl_selesai'))) }} @endif
                            @if(request('status') && request('status') !== 'Semua') | Status: {{ request('status') === 'Pinjam' ? 'Dipinjam' : 'Dikembalikan' }} @endif
                            @if(request('anggota_id')) | Anggota: {{ $anggotas->firstWhere('id', request('anggota_id'))->nama ?? '' }} @endif
                        @endif
                    </p>
                </div>

                {{-- Ringkasan Statistik --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light text-center h-100">
                            <h6 class="text-muted mb-1 text-uppercase">Total Transaksi</h6>
                            <h2 class="fw-bold mb-0 text-primary">{{ $totalTransaksi }}</h2>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light text-center h-100">
                            <h6 class="text-muted mb-1 text-uppercase">Total Denda</h6>
                            <h2 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>

                {{-- Tabel Transaksi --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Kode Transaksi</th>
                                <th width="20%">Anggota</th>
                                <th width="25%">Buku</th>
                                <th width="12%">Tgl Pinjam</th>
                                <th width="12%">Tgl Kembali</th>
                                <th width="12%">Status</th>
                                <th class="text-end" width="12%">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $transaksi->kode_transaksi }}</strong></td>
                                    <td>
                                        {{ $transaksi->anggota->nama ?? '-' }}
                                        <br><small class="text-muted">{{ $transaksi->anggota->kode_anggota ?? '' }}</small>
                                    </td>
                                    <td>
                                        {{ $transaksi->buku->judul ?? '-' }}
                                        <br><small class="text-muted">{{ $transaksi->buku->kode_buku ?? '' }}</small>
                                    </td>
                                    <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($transaksi->status === 'Dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td class="text-end">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle display-4 mb-2 d-block"></i>
                                        Tidak ada data transaksi yang cocok dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Load html2pdf.js dari CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('btn-pdf-export').addEventListener('click', function(e) {
        e.preventDefault();
        
        const element = document.getElementById('laporan-cetak');
        
        // Konfigurasi ekspor PDF
        const opt = {
            margin:       10,
            filename:     'laporan_transaksi_{{ date("Ymd_His") }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };
        
        // Eksekusi download PDF
        html2pdf().set(opt).from(element).save();
    });
</script>
@endpush
</x-app-layout>
