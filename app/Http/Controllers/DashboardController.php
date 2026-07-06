<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $stats = [
            'total_buku'        => Buku::count(),
            'total_anggota'     => Anggota::where('status', 'Aktif')->count(),
            'total_transaksi'   => Transaksi::count(),
            'sedang_dipinjam'   => Transaksi::where('status', 'Dipinjam')->count(),
            'terlambat'         => Transaksi::where('status', 'Dipinjam')
                ->where('tanggal_kembali', '<', now())->count(),
            'denda_bulan_ini'   => Transaksi::whereMonth('tanggal_dikembalikan', now()->month)
                ->sum('denda'),
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_pinjam', today())->count(),
            'buku_tersedia'     => Buku::where('stok', '>', 0)->count(),
        ];

        // Data chart: transaksi 6 bulan terakhir
        $chartData = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'bulan' => $date->translatedFormat('M Y'),
                'pinjam' => Transaksi::whereMonth('tanggal_pinjam', $date->month)
                    ->whereYear('tanggal_pinjam', $date->year)->count(),
                'kembali' => Transaksi::whereMonth('tanggal_dikembalikan', $date->month)
                    ->whereYear('tanggal_dikembalikan', $date->year)->count(),
            ];
        });

        // Top 10 buku populer
        $bukuPopuler = Buku::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(10)->get();

        // Data Pie chart: Kategori Buku
        $kategoriBuku = Kategori::withCount('bukus')
            ->having('bukus_count', '>', 0)
            ->get();

        // Data Donut chart: Status Transaksi
        $statusTransaksi = Transaksi::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top 5 anggota aktif
        $anggotaAktif = Anggota::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(5)->get();

        // Data Buku Terlambat
        $bukuTerlambat = Transaksi::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now()->startOfDay())
            ->with(['anggota', 'buku'])
            ->get();

        // Transaksi terbaru
        $recentTransaksi = Transaksi::with(['anggota', 'buku'])
            ->latest()->take(5)->get();

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
