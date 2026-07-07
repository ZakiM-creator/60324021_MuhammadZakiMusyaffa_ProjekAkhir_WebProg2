<?php

// ============================================================
// FILE: LaporanController.php
// FUNGSI: Menampilkan laporan transaksi dengan fitur filter
// ============================================================
// Controller ini menyediakan halaman laporan alternatif.
// Fitur utama laporan sudah ditangani TransaksiController@laporan.
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;                  // Model untuk tabel transaksis
use App\Models\Anggota;                    // Model untuk tabel anggota
use Carbon\Carbon;                         // Library manipulasi tanggal/waktu

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan transaksi dengan fitur filter.
     *
     * @param Request $request — berisi parameter filter dari form
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // LANGKAH 1: Buat query builder dengan Eager Loading relasi.
        // with(['anggota','buku']) = memuat data tabel anggota & buku dalam 1 query
        // (mencegah N+1 problem yang memperlambat performa).
        $query = Transaksi::with(['anggota', 'buku']);

        // LANGKAH 2: Filter berdasarkan tanggal (jika user mengisi).
        // filled() mengecek apakah field diisi DAN bukan string kosong.
        // whereDate() membandingkan hanya bagian tanggal (mengabaikan waktu/jam).
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        // LANGKAH 3: Filter berdasarkan status (Dipinjam/Dikembalikan).
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // LANGKAH 4: Filter berdasarkan anggota tertentu.
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        // LANGKAH 5: Eksekusi query. latest() = ORDER BY created_at DESC.
        $transaksis = $query->latest()->get();

        // LANGKAH 6: Hitung statistik ringkasan dari hasil query (Collection method).
        // Ini dihitung di level PHP (bukan di database).
        $summary = [
            'total'          => $transaksis->count(),                                    // Total transaksi
            'dipinjam'       => $transaksis->where('status', 'Dipinjam')->count(),        // Yang masih dipinjam
            'dikembalikan'   => $transaksis->where('status', 'Dikembalikan')->count(),    // Yang sudah dikembalikan
            'total_denda'    => $transaksis->sum('denda'),                                // Jumlah total denda (Rp)
        ];

        // LANGKAH 7: Ambil daftar semua anggota (untuk dropdown filter di view).
        $anggotas = Anggota::orderBy('nama')->get();

        // Kirim semua data ke view laporan/index.blade.php
        return view('laporan.index', compact('transaksis', 'summary', 'anggotas'));
    }
}
