{{-- 
============================================================
FILE: transaksi/laporan.blade.php
FUNGSI: Menampilkan halaman filter dan laporan transaksi peminjaman perpustakaan
============================================================
--}}
{{-- Memanggil komponen layout utama (app.blade.php) dengan judul halaman --}}
<x-app-layout theme="bootstrap" title="Laporan Transaksi">
<div class="row mb-4">
    <div class="col-12">
        {{-- Card untuk Filter Laporan --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-funnel me-1"></i> Filter Laporan Transaksi
                </h5>
            </div>
            <div class="card-body p-4">
                {{-- Form untuk memfilter data, menggunakan GET ke route transaksi.laporan --}}
                <form action="{{ route('transaksi.laporan') }}" method="GET">
                    <div class="row g-3">
                        {{-- FILTER: Rentang Tanggal (Dari) --}}
                        <div class="col-md-3">
                            <label for="tgl_mulai" class="form-label fw-bold">Dari Tanggal</label>
                            {{-- Input tanggal mulai, dengan mempertahankan value dari request --}}
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" 
                                   value="{{ request('tgl_mulai') }}">
                        </div>
                        {{-- FILTER: Rentang Tanggal (Sampai) --}}
                        <div class="col-md-3">
                            <label for="tgl_selesai" class="form-label fw-bold">Sampai Tanggal</label>
                            {{-- Input tanggal selesai, dengan mempertahankan value dari request --}}
                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" 
                                   value="{{ request('tgl_selesai') }}">
                        </div>

                        {{-- FILTER: Status Peminjaman --}}
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-bold">Status Peminjaman</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Semua" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        {{-- FILTER: Anggota --}}
                        <div class="col-md-3">
                            <label for="anggota_id" class="form-label fw-bold">Anggota</label>
                            <select name="anggota_id" id="anggota_id" class="form-select">
                                <option value="">Semua Anggota</option>
                                {{-- Looping data anggota untuk dropdown --}}
                                @foreach($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tombol Aksi Form --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        {{-- Tombol Reset, akan mengarahkan ke halaman laporan tanpa query string --}}
                        <a href="{{ route('transaksi.laporan') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        {{-- Tombol Terapkan Filter (Submit) --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- AREA KONTEN LAPORAN & EXPORT PDF --}}
<div class="row">
    <div class="col-12">
        {{-- Tombol Export PDF --}}
        <div class="d-flex justify-content-end mb-3">
            {{-- Link untuk export PDF, mengirim seluruh request filter yang sedang aktif (request()->all()) --}}
            <a href="{{ route('transaksi.export.pdf', request()->all()) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf me-1"></i> Export PDF
            </a>
        </div>

        {{-- Card Laporan yang ditampilkan di layar --}}
        <div class="card shadow-sm border-0">
            <div id="laporan-cetak" class="card-body p-4 bg-white">
                {{-- BAGIAN: Kop Laporan --}}
                <div class="text-center mb-4 pb-3 border-bottom">
                    <h2 class="fw-bold mb-1">LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
                    <h5 class="text-muted mb-2">Sistem Manajemen Perpustakaan Laravel</h5>
                    <p class="mb-0 small text-muted">
                        {{-- Waktu pencetakan laporan --}}
                        Dicetak pada: {{ date('d F Y H:i') }}
                        
                        {{-- Menampilkan info filter apa saja yang sedang aktif --}}
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

                {{-- BAGIAN: Ringkasan Statistik Laporan --}}
                <div class="row mb-4">
                    {{-- Total Transaksi --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light text-center h-100">
                            <h6 class="text-muted mb-1 text-uppercase">Total Transaksi</h6>
                            {{-- Mengambil nilai $totalTransaksi dari controller --}}
                            <h2 class="fw-bold mb-0 text-primary">{{ $totalTransaksi }}</h2>
                        </div>
                    </div>
                    {{-- Total Denda --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light text-center h-100">
                            <h6 class="text-muted mb-1 text-uppercase">Total Denda</h6>
                            {{-- Menampilkan nilai $totalDenda yang sudah diformat rupiah --}}
                            <h2 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN: Tabel Data Transaksi --}}
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
                            {{-- Looping daftar transaksi yang sudah difilter --}}
                            @forelse($transaksis as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $transaksi->kode_transaksi }}</strong></td>
                                    <td>
                                        {{-- Nama dan kode anggota --}}
                                        {{ $transaksi->anggota->nama ?? '-' }}
                                        <br><small class="text-muted">{{ $transaksi->anggota->kode_anggota ?? '' }}</small>
                                    </td>
                                    <td>
                                        {{-- Judul dan kode buku --}}
                                        {{ $transaksi->buku->judul ?? '-' }}
                                        <br><small class="text-muted">{{ $transaksi->buku->kode_buku ?? '' }}</small>
                                    </td>
                                    <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        {{-- Status badge --}}
                                        @if($transaksi->status === 'Dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    {{-- Kolom denda dengan format angka --}}
                                    <td class="text-end">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                {{-- Pesan jika tabel kosong/tidak ada hasil filter --}}
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


</x-app-layout>
