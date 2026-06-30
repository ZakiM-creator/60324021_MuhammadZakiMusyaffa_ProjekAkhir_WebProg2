<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan statistik perpustakaan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ========== STATISTIK BUKU ==========
        
        // Total semua buku di database
        $totalBuku = Buku::count();
        
        // Buku tersedia (stok > 0) menggunakan scope dari Model
        $bukuTersedia = Buku::tersedia()->count();
        
        // Buku habis (stok = 0)
        $bukuHabis = Buku::where('stok', 0)->count();
        
        
        // ========== STATISTIK ANGGOTA ==========
        
        // Total semua anggota
        $totalAnggota = Anggota::count();
        
        // Anggota aktif menggunakan scope dari Model
        $anggotaAktif = Anggota::aktif()->count();
        
        // Anggota nonaktif
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();
        
        
        // ========== DATA TERBARU ==========
        
        // 5 Buku terbaru berdasarkan created_at (data terakhir ditambahkan)
        // orderBy('created_at', 'desc') = urutkan dari yang terbaru
        // limit(5) = ambil hanya 5 data
        $bukuTerbaru = Buku::orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();
        
        // 5 Anggota terbaru berdasarkan tanggal_daftar
        $anggotaTerbaru = Anggota::orderBy('tanggal_daftar', 'desc')
                                ->limit(5)
                                ->get();
        
        
        // ========== STATISTIK KETERLAMBATAN ==========
        
        // Transaksi berstatus 'Pinjam' dan tanggal pinjam > 7 hari yang lalu
        $transaksiTerlambat = Transaksi::with(['buku', 'anggota'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now()->toDateString())
            ->get();
            
        $totalTerlambat = $transaksiTerlambat->count();
        
        
        // ========== KIRIM DATA KE VIEW ==========
        
        // Menggunakan compact() untuk mengirim semua variabel ke view
        // Variabel akan tersedia di view dengan nama yang sama
        return view('dashboard.index', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru',
            'transaksiTerlambat',
            'totalTerlambat'
        ));
    }
}
