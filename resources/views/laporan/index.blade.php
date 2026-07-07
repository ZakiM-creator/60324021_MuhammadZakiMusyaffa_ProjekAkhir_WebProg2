{{-- 
============================================================
FILE: laporan/index.blade.php
FUNGSI: Halaman laporan transaksi alternatif (atau utama) dengan filter, ringkasan kartu, dan fitur cetak langsung (window.print)
============================================================
--}}
{{-- Memanggil komponen layout utama (app.blade.php) dengan judul halaman --}}
<x-app-layout theme="bootstrap" title="Laporan Transaksi">
<div class="container py-4">
    <h2>Laporan Transaksi</h2>

    {{-- BAGIAN: Form Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            {{-- Form GET untuk filtering data laporan, tanpa action spesifik berarti submit ke URL saat ini --}}
            <form method="GET" class="row g-3">
                {{-- Filter Rentang Tanggal (Dari) --}}
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control"
                        value="{{ request('dari') }}">
                </div>
                {{-- Filter Rentang Tanggal (Sampai) --}}
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control"
                        value="{{ request('sampai') }}">
                </div>
                {{-- Filter Status --}}
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                {{-- Filter Anggota --}}
                <div class="col-md-3">
                    <label class="form-label">Anggota</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">Semua</option>
                        {{-- Looping daftar anggota --}}
                        @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                            {{ $anggota->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                {{-- Tombol Aksi Form --}}
                <div class="col-12">
                    {{-- Tombol untuk submit filter --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    {{-- Tombol untuk mereset filter dengan mengarahkan ulang ke halaman tanpa query parameter --}}
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                    {{-- Tombol untuk mencetak halaman (menggunakan fungsi bawaan browser window.print()) --}}
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- BAGIAN: Kartu Ringkasan (Summary Cards) --}}
    <div class="row g-3 mb-4">
        {{-- Card Total Transaksi --}}
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6>Total Transaksi</h6>
                    <h3>{{ $summary['total'] }}</h3>
                </div>
            </div>
        </div>
        {{-- Card Transaksi Dipinjam --}}
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h6>Dipinjam</h6>
                    <h3>{{ $summary['dipinjam'] }}</h3>
                </div>
            </div>
        </div>
        {{-- Card Transaksi Dikembalikan --}}
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6>Dikembalikan</h6>
                    <h3>{{ $summary['dikembalikan'] }}</h3>
                </div>
            </div>
        </div>
        {{-- Card Total Denda Terkumpul --}}
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6>Total Denda</h6>
                    <h3>Rp {{ number_format($summary['total_denda'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN: Tabel Laporan Transaksi --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Looping daftar transaksi --}}
                    @forelse($transaksis as $i => $trx)
                    <tr>
                        {{-- Nomor urut menggunakan index looping + 1 --}}
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->anggota->nama }}</td>
                        <td>{{ $trx->buku->judul }}</td>
                        {{-- Memformat tanggal peminjaman --}}
                        <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                        {{-- Memformat tanggal dikembalikan menggunakan nullsafe operator (?->) dan null coalescing (??) --}}
                        <td>{{ $trx->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            {{-- Badge status dinamis: warning untuk Dipinjam, success untuk selainnya (Dikembalikan) --}}
                            <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                        {{-- Menampilkan denda jika ada, jika tidak (0) tampilkan tanda hubung (-) --}}
                        <td>{{ $trx->denda ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                    </tr>
                    @empty
                    {{-- Ditampilkan jika tidak ada data dari hasil query filter --}}
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- BAGIAN: Print CSS (Gaya khusus untuk pencetakan) --}}
{{-- @media print berfungsi agar elemen yang tidak perlu dicetak disembunyikan saat window.print() dipanggil --}}
<style>
    @media print {
        /* Sembunyikan form filter, tombol-tombol, navbar, dan footer */
        .card-body form,
        .btn,
        nav,
        footer {
            display: none !important;
        }

        /* Hapus border dan shadow (bayangan) dari card agar terlihat lebih rapi saat dicetak */
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
</x-app-layout>