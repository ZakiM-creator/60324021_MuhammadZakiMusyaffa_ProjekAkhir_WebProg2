<?php

// ============================================================
// FILE: PerpustakaanController.php
// FUNGSI: Halaman statis perpustakaan (versi prototype/awal)
// ============================================================
// Controller ini adalah versi AWAL/PROTOTYPE dari pertemuan
// sebelumnya yang menggunakan data statis (hardcoded array),
// bukan database. Ia tetap ada sebagai referensi historis.
// ============================================================

// Namespace controller sesuai konvensi struktur folder Laravel
namespace App\Http\Controllers;

// Import class Request dari Laravel
// Meskipun belum digunakan secara langsung di method manapun,
// import ini disiapkan untuk kebutuhan pengembangan selanjutnya
// (misalnya jika nanti perlu menerima query parameter)
use Illuminate\Http\Request;

// ============================================================
// Class PerpustakaanController
// Controller ini menggunakan data STATIS (hardcoded array)
// bukan dari database. Semua data buku didefinisikan langsung
// di dalam method sebagai array PHP.
// Cocok untuk prototype awal atau pembelajaran dasar Laravel.
// ============================================================
class PerpustakaanController extends Controller
{
    // =========================================================
    // Method: index()
    // Menampilkan halaman utama perpustakaan dengan daftar buku.
    //
    // Data yang dikirim ke view:
    //  - $nama_sistem : nama aplikasi perpustakaan
    //  - $versi       : versi Laravel yang digunakan
    //  - $total_buku  : jumlah total buku (hardcoded)
    //  - $buku_list   : array berisi data 5 buku statis
    //
    // Catatan: Semua data di sini bersifat hardcoded (statis),
    // tidak diambil dari database. Pada versi production,
    // data ini akan diganti dengan query ke database.
    // =========================================================
    public function index()
    {
        // Variabel statis: nama sistem perpustakaan
        $nama_sistem = "Sistem Perpustakaan Laravel";

        // Variabel statis: versi framework yang digunakan
        $versi = "12.x";

        // Variabel statis: jumlah total buku yang tersedia
        $total_buku = 5;

        // -------------------------------------------------------
        // Array statis berisi daftar buku
        // Setiap elemen array adalah array asosiatif dengan key:
        //  - id        : ID unik buku
        //  - judul     : judul buku
        //  - pengarang : nama pengarang/penulis
        //  - harga     : harga buku dalam Rupiah
        //  - stok      : jumlah stok yang tersedia
        // -------------------------------------------------------
        $buku_list = [
            [
                'id' => 1,
                'judul' => 'Pemrograman PHP',
                'pengarang' => 'Budi Raharjo',
                'harga' => 75000,
                'stok' => 10
            ],
            [
                'id' => 2,
                'judul' => 'Laravel Framework',
                'pengarang' => 'Andi Nugroho',
                'harga' => 125000,
                'stok' => 5
            ],
            [
                'id' => 3,
                'judul' => 'MySQL Database',
                'pengarang' => 'Siti Aminah',
                'harga' => 95000,
                'stok' => 0
            ],
            [
                'id' => 4,
                'judul' => 'Web Design',
                'pengarang' => 'Dedi Santoso',
                'harga' => 85000,
                'stok' => 8
            ],
            [
                'id' => 5,
                'judul' => 'JavaScript Modern',
                'pengarang' => 'Rina Wijaya',
                'harga' => 80000,
                'stok' => 12
            ]
        ];

        // Kirim semua variabel ke view 'perpustakaan.index' menggunakan compact()
        // compact() secara otomatis membuat array asosiatif dari nama variabel:
        // ['nama_sistem' => $nama_sistem, 'versi' => $versi, 'total_buku' => $total_buku, 'buku_list' => $buku_list]
        return view('perpustakaan.index', compact('nama_sistem', 'versi', 'total_buku', 'buku_list'));
    }

    // =========================================================
    // Method: show($id)
    // Menampilkan halaman detail satu buku berdasarkan ID.
    //
    // Parameter:
    //  - $id : ID buku yang ingin ditampilkan (dari URL segment)
    //
    // Perbedaan dengan index():
    //  - Array menggunakan KEY ASOSIATIF (1 =>, 2 =>) agar bisa
    //    dicari langsung berdasarkan ID tanpa loop
    //  - Data buku lebih lengkap (ada penerbit, tahun, deskripsi)
    //
    // Error handling:
    //  - Jika $id tidak ada di array → abort(404)
    //
    // Catatan: Data statis, nanti akan diganti query database.
    // =========================================================
    public function show($id)
    {
        // -------------------------------------------------------
        // Array asosiatif berisi detail buku
        // Key array = ID buku, sehingga bisa diakses langsung: $buku_list[$id]
        // Data lebih lengkap dibanding index() karena ini halaman detail:
        //  - penerbit  : nama penerbit buku
        //  - tahun     : tahun terbit
        //  - deskripsi : sinopsis/deskripsi singkat buku
        //
        // Catatan: Hanya 2 buku yang didefinisikan sebagai contoh.
        // Pada versi production, data akan diambil dari database.
        // -------------------------------------------------------
        $buku_list = [
            1 => [
                'id' => 1,
                'judul' => 'Pemrograman PHP',
                'pengarang' => 'Budi Raharjo',
                'penerbit' => 'Informatika',
                'tahun' => 2023,
                'harga' => 75000,
                'stok' => 10,
                'deskripsi' => 'Buku panduan lengkap pemrograman PHP dari dasar hingga advanced'
            ],
            2 => [
                'id' => 2,
                'judul' => 'Laravel Framework',
                'pengarang' => 'Andi Nugroho',
                'penerbit' => 'Graha Ilmu',
                'tahun' => 2024,
                'harga' => 125000,
                'stok' => 5,
                'deskripsi' => 'Membangun aplikasi web modern dengan Laravel framework'
            ],
            // ... data lainnya
        ];

        // -------------------------------------------------------
        // VALIDASI: Cek apakah buku dengan ID tersebut ada di array
        // isset() memeriksa apakah key $id ada di array $buku_list.
        // Jika tidak ada, abort(404) akan menampilkan halaman 404 Not Found
        // dengan pesan custom 'Buku tidak ditemukan'.
        // -------------------------------------------------------
        if (!isset($buku_list[$id])) {
            abort(404, 'Buku tidak ditemukan');
        }

        // Ambil data buku berdasarkan ID dari array asosiatif
        $buku = $buku_list[$id];

        // Kirim data buku ke view 'perpustakaan.show' untuk ditampilkan
        return view('perpustakaan.show', compact('buku'));
    }

    // =========================================================
    // Method: about()
    // Menampilkan halaman "Tentang" aplikasi perpustakaan.
    //
    // Data statis berisi informasi umum aplikasi:
    //  - nama      : nama sistem
    //  - versi     : versi aplikasi
    //  - deskripsi : penjelasan singkat sistem
    //  - developer : nama pengembang
    //  - tahun     : tahun saat ini (otomatis dari date('Y'))
    //
    // date('Y') menghasilkan tahun dinamis sehingga halaman
    // about selalu menampilkan tahun terkini tanpa perlu
    // diupdate manual setiap tahun.
    // =========================================================
    public function about()
    {
        // Array asosiatif berisi informasi statis tentang aplikasi
        // Key 'tahun' menggunakan date('Y') agar tahun otomatis update
        // sesuai tahun server saat halaman diakses
        $info = [
            'nama' => 'Sistem Perpustakaan Laravel',
            'versi' => '1.0.0',
            'deskripsi' => 'Sistem manajemen perpustakaan berbasis Laravel framework',
            'developer' => 'Nama Mahasiswa',
            'tahun' => date('Y')
        ];

        // Kirim data info ke view 'perpustakaan.about'
        return view('perpustakaan.about', compact('info'));
    }
}
