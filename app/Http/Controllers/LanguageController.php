<?php

// ============================================================
// FILE: LanguageController.php
// FUNGSI: Menangani pergantian bahasa aplikasi (ID/EN)
// ROUTE: GET /lang/{lang}  →  contoh: /lang/en atau /lang/id
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;               // Class untuk menangani data HTTP request
use Illuminate\Support\Facades\Session;    // Facade untuk penyimpanan data sementara per-user (session)

class LanguageController extends Controller // Mewarisi base Controller
{
    /**
     * Mengganti bahasa aplikasi.
     * Dipanggil saat user meng-klik tombol ganti bahasa di navbar.
     *
     * @param string $lang — kode bahasa dari URL ('en' atau 'id')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($lang)
    {
        // LANGKAH 1: Validasi keamanan.
        // Pastikan $lang hanya berisi 'en' atau 'id'.
        // in_array() memeriksa apakah $lang ada di dalam array ['en','id'].
        // Ini mencegah user iseng mengetik /lang/hacker di URL.
        if (in_array($lang, ['en', 'id'])) {

            // LANGKAH 2: Simpan pilihan bahasa ke Session.
            // Session::put('locale', 'en') → menyimpan key 'locale' dengan value 'en'.
            // Data session ini akan dibaca oleh Middleware SetLocale
            // pada setiap request berikutnya untuk mengatur App::setLocale().
            Session::put('locale', $lang);
        }
        // Jika $lang bukan 'en'/'id', tidak terjadi apa-apa (diabaikan).

        // LANGKAH 3: Redirect kembali ke halaman sebelumnya.
        // redirect()->back() mengirim user kembali ke URL asal.
        // User klik ganti bahasa → halaman reload dengan bahasa baru.
        return redirect()->back();
    }
}
