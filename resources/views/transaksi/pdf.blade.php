{{-- 
============================================================
FILE: transaksi/pdf.blade.php
FUNGSI: Template untuk mengekspor (mencetak) laporan transaksi ke format PDF menggunakan dompdf
============================================================
--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Perpustakaan</title>
    {{-- Internal CSS untuk styling dokumen PDF agar rapi --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .filter-info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-box {
            float: right;
            width: 300px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .summary-box table {
            border: none;
            margin: 0;
        }
        .summary-box th, .summary-box td {
            border: none;
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    {{-- BAGIAN KOP LAPORAN --}}
    <div class="header">
        <h1>LAPORAN TRANSAKSI PERPUSTAKAAN</h1>
        {{-- Menampilkan tanggal pencetakan secara real-time --}}
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    {{-- INFORMASI FILTER YANG DITERAPKAN --}}
    <div class="filter-info">
        <strong>Filter:</strong>
        {{-- Jika ada filter rentang tanggal --}}
        @if(request('tanggal_mulai') || request('tanggal_selesai'))
            Periode: {{ request('tanggal_mulai') ?? '-' }} s/d {{ request('tanggal_selesai') ?? '-' }} |
        @endif
        {{-- Jika ada filter status peminjaman --}}
        @if(request('status'))
            Status: {{ ucfirst(request('status')) }} |
        @endif
        {{-- Jika ada filter spesifik anggota --}}
        @if(request('anggota_id'))
            Anggota: {{ $transaksis->first()->anggota->nama ?? request('anggota_id') }}
        @endif
    </div>

    {{-- TABEL DATA TRANSAKSI --}}
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Kode Transaksi</th>
                <th width="20%">Peminjam</th>
                <th width="20%">Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Tgl Kembali</th>
                <th width="8%" class="text-center">Status</th>
                <th width="8%" class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            {{-- Melakukan iterasi (loop) terhadap collection $transaksis --}}
            @forelse($transaksis as $index => $transaksi)
                <tr>
                    {{-- Penomoran berurutan --}}
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $transaksi->kode_transaksi }}</td>
                    {{-- Data relasi anggota --}}
                    <td>{{ $transaksi->anggota->nama }}</td>
                    {{-- Data relasi buku --}}
                    <td>{{ $transaksi->buku->judul }}</td>
                    {{-- Memformat tanggal dengan library Carbon --}}
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $transaksi->status }}</td>
                    {{-- Format uang rupiah untuk denda --}}
                    <td class="text-right">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                </tr>
            @empty
                {{-- Tampilan saat data kosong --}}
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- KOTAK RINGKASAN STATISTIK (di pojok kanan bawah tabel) --}}
    <div class="summary-box">
        <table>
            <tr>
                <th>Total Transaksi</th>
                {{-- Jika $totalTransaksi tidak ada, hitung count() dari collection $transaksis --}}
                <td class="text-right">{{ $totalTransaksi ?? $transaksis->count() }}</td>
            </tr>
            <tr>
                <th>Total Dipinjam</th>
                <td class="text-right">{{ $totalDipinjam ?? $transaksis->where('status', 'Dipinjam')->count() }}</td>
            </tr>
            <tr>
                <th>Total Dikembalikan</th>
                <td class="text-right">{{ $totalDikembalikan ?? $transaksis->where('status', 'Dikembalikan')->count() }}</td>
            </tr>
            <tr>
                <th>Total Denda</th>
                {{-- Jika $totalDenda tidak ada, jumlahkan nilai kolom 'denda' dari collection --}}
                <td class="text-right">Rp {{ number_format($totalDenda ?? $transaksis->sum('denda'), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    
    {{-- Clearfix untuk menangani float element (summary-box) --}}
    <div class="clear"></div>
    
    {{-- BAGIAN TANDA TANGAN (FOOTER) --}}
    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin Perpustakaan</strong></p>
    </div>
</body>
</html>
