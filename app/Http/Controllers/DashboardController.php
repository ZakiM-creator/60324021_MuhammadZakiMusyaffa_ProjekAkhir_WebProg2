<?php

// ============================================================
// FILE: DashboardController.php
// FUNGSI: Menampilkan halaman dashboard utama dengan
//         statistik ringkasan dan 4 jenis grafik (chart)
// ROUTE: GET /dashboard
// ============================================================

namespace App\Http\Controllers;

use App\Models\Buku;                       // Model tabel buku
use App\Models\Anggota;                    // Model tabel anggota
use App\Models\Transaksi;                  // Model tabel transaksis
use App\Models\Kategori;                   // Model tabel kategori
use Carbon\Carbon;                         // Library manipulasi tanggal
use Illuminate\Support\Facades\DB;         // Facade untuk raw database query

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     * Mengumpulkan semua statistik dan data chart dari database.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ═══════════ STATISTIK UTAMA (Card di atas dashboard) ═══════════

        $stats = [
            // Buku::count() → SELECT COUNT(*) FROM buku
            'total_buku'        => Buku::count(),

            // Hanya anggota yang berstatus 'Aktif'
            'total_anggota'     => Anggota::where('status', 'Aktif')->count(),

            // Total semua transaksi yang pernah dibuat
            'total_transaksi'   => Transaksi::count(),

            // Transaksi yang statusnya masih 'Dipinjam' (belum dikembalikan)
            'sedang_dipinjam'   => Transaksi::where('status', 'Dipinjam')->count(),

            // Buku terlambat = status masih Dipinjam DAN tanggal_kembali sudah lewat hari ini
            'terlambat'         => Transaksi::where('status', 'Dipinjam')
                ->where('tanggal_kembali', '<', now())->count(),

            // Total denda bulan ini. whereMonth() filter berdasarkan bulan.
            // now()->month mengembalikan angka bulan saat ini (misal: 7 untuk Juli).
            'denda_bulan_ini'   => Transaksi::whereMonth('tanggal_dikembalikan', now()->month)
                ->sum('denda'),

            // Transaksi yang dibuat hari ini. whereDate() + today() = tanggal hari ini.
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_pinjam', today())->count(),

            // Jumlah buku yang masih punya stok (stok > 0)
            'buku_tersedia'     => Buku::where('stok', '>', 0)->count(),
        ];

        // ═══════════ LINE CHART: Trend Peminjaman 6 Bulan Terakhir ═══════════

        // range(5, 0) menghasilkan array [5, 4, 3, 2, 1, 0]
        // collect() mengubahnya menjadi Laravel Collection agar bisa pakai ->map()
        // map() menjalankan fungsi untuk setiap elemen, menghasilkan array data chart.
        $chartData = collect(range(5, 0))->map(function ($i) {
            // subMonths($i) mengurangi $i bulan dari tanggal sekarang.
            // Jika sekarang Juli 2026: $i=5 → Feb 2026, $i=0 → Jul 2026
            $date = now()->subMonths($i);
            return [
                // translatedFormat('M Y') → format bulan dalam bahasa lokal: "Jul 2026"
                'bulan' => $date->translatedFormat('M Y'),

                // Hitung jumlah peminjaman pada bulan & tahun tersebut
                'pinjam' => Transaksi::whereMonth('tanggal_pinjam', $date->month)
                    ->whereYear('tanggal_pinjam', $date->year)->count(),

                // Hitung jumlah pengembalian pada bulan & tahun tersebut
                'kembali' => Transaksi::whereMonth('tanggal_dikembalikan', $date->month)
                    ->whereYear('tanggal_dikembalikan', $date->year)->count(),
            ];
        });

        // ═══════════ BAR CHART: Top 10 Buku Terpopuler ═══════════

        // withCount('transaksis') menambah kolom virtual 'transaksis_count'
        // yang berisi jumlah transaksi untuk setiap buku.
        // orderByDesc → urutkan dari yang paling banyak dipinjam.
        // take(10) → ambil 10 teratas saja.
        $bukuPopuler = Buku::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(10)->get();

        // ═══════════ PIE CHART: Distribusi Kategori Buku ═══════════

        // withCount('bukus') → hitung jumlah buku per kategori
        // having('bukus_count', '>', 0) → hanya kategori yang memiliki buku (>0)
        $kategoriBuku = Kategori::withCount('bukus')
            ->having('bukus_count', '>', 0)
            ->get();

        // ═══════════ DONUT CHART: Status Transaksi ═══════════

        // Raw SQL: SELECT status, COUNT(*) as count FROM transaksis GROUP BY status
        // DB::raw() memungkinkan penulisan ekspresi SQL mentah.
        // groupBy('status') mengelompokkan data berdasarkan status.
        $statusTransaksi = Transaksi::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // ═══════════ TOP 5 ANGGOTA PALING AKTIF ═══════════

        // withCount('transaksis') → hitung jumlah transaksi per anggota
        // Urutkan dari yang paling banyak transaksi, ambil 5 teratas.
        $anggotaAktif = Anggota::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(5)->get();

        // ═══════════ DAFTAR BUKU YANG TERLAMBAT DIKEMBALIKAN ═══════════

        // Cari transaksi yang masih berstatus 'Dipinjam' DAN tanggal_kembali sudah lewat.
        // startOfDay() = set waktu ke 00:00:00 (bandingkan tanggal saja, bukan jam).
        $bukuTerlambat = Transaksi::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now()->startOfDay())
            ->with(['anggota', 'buku'])     // Eager load relasi untuk performa
            ->get();

        // ═══════════ 5 TRANSAKSI TERBARU ═══════════

        // latest() = ORDER BY created_at DESC, take(5) = LIMIT 5
        $recentTransaksi = Transaksi::with(['anggota', 'buku'])
            ->latest()->take(5)->get();

        // ═══════════ KIRIM SEMUA DATA KE VIEW ═══════════

        // compact() membungkus 8 variabel menjadi array asosiatif.
        // View 'dashboard' akan menggunakan data ini untuk merender
        // card statistik & chart (Chart.js).
        return view('dashboard', compact(
            'stats',
            'chartData',
            'bukuPopuler',
            'kategoriBuku',
            'statusTransaksi',
            'anggotaAktif',
            'bukuTerlambat',
            'recentTransaksi'
        ));
    }
}
