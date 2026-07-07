<?php

// ============================================================
// FILE: SearchController.php
// FUNGSI: Pencarian global dari navbar (search bar di atas)
// ROUTE: GET /search?q=keyword
// ============================================================
// Controller ini mencari keyword di 3 tabel sekaligus:
// buku, anggota, dan transaksi.
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;                       // Model Eloquent untuk tabel `buku`
use App\Models\Anggota;                    // Model Eloquent untuk tabel `anggota`
use App\Models\Transaksi;                  // Model Eloquent untuk tabel `transaksis`

class SearchController extends Controller
{
    /**
     * Pencarian global dari search bar navbar.
     * Mencari keyword di 3 tabel sekaligus: buku, anggota, transaksi.
     *
     * @param Request $request — berisi query string ?q=keyword
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // LANGKAH 1: Ambil keyword dari query string URL.
        // Contoh: /search?q=laravel → $keyword = 'laravel'
        // $request->input('q') membaca parameter 'q' dari URL.
        $keyword = $request->input('q');

        // LANGKAH 2: Inisialisasi array hasil pencarian dengan koleksi kosong.
        // collect() membuat Laravel Collection kosong (seperti array pintar).
        // Ini memastikan view tidak error meskipun keyword kosong.
        $results = ['buku' => collect(), 'anggota' => collect(), 'transaksi' => collect()];

        // LANGKAH 3: Jika keyword diisi (bukan null/kosong), lakukan pencarian.
        if ($keyword) {

            // LANGKAH 3a: Cari di tabel BUKU.
            // WHERE judul LIKE '%laravel%' OR pengarang LIKE '%laravel%' OR isbn LIKE '%laravel%'
            // LIKE dengan % di kedua sisi = cari substring di mana saja dalam string.
            // get() = eksekusi query, kembalikan Collection hasil.
            $results['buku'] = Buku::where('judul', 'LIKE', "%{$keyword}%")
                ->orWhere('pengarang', 'LIKE', "%{$keyword}%")
                ->orWhere('isbn', 'LIKE', "%{$keyword}%")
                ->get();

            // LANGKAH 3b: Cari di tabel ANGGOTA.
            // Mencari di kolom nama, email, dan kode_anggota.
            $results['anggota'] = Anggota::where('nama', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")
                ->orWhere('kode_anggota', 'LIKE', "%{$keyword}%")
                ->get();

            // LANGKAH 3c: Cari di tabel TRANSAKSI (termasuk relasi).
            // with(['anggota','buku']) = Eager Loading, memuat data relasi sekaligus.
            // orWhereHas() = cari di tabel relasi (jika nama anggota atau judul buku cocok).
            // fn($q) => ... adalah Arrow Function PHP 7.4+ (shorthand closure).
            $results['transaksi'] = Transaksi::with(['anggota', 'buku'])
                ->where('kode_transaksi', 'LIKE', "%{$keyword}%")
                ->orWhereHas('anggota', fn($q) => $q->where('nama', 'LIKE', "%{$keyword}%"))
                ->orWhereHas('buku', fn($q) => $q->where('judul', 'LIKE', "%{$keyword}%"))
                ->get();
        }

        // LANGKAH 4: Kirim data ke view untuk ditampilkan.
        // compact() = membungkus variabel menjadi array asosiatif.
        // compact('keyword','results') setara ['keyword'=>$keyword, 'results'=>$results]
        return view('search.index', compact('keyword', 'results'));
    }
}
