<?php

// ============================================================
// FILE: AnggotaController.php
// FUNGSI: Mengelola CRUD data Anggota Perpustakaan,
//         Advanced Search & Filter, Export Excel
// ROUTE: Resource route → /anggota + custom routes
// ============================================================

// Namespace controller sesuai struktur folder Laravel
namespace App\Http\Controllers;

// Import model Anggota untuk interaksi dengan tabel 'anggotas' di database
use App\Models\Anggota;

// Import class Request bawaan Laravel untuk menangani HTTP request (query string, input form, dll)
use Illuminate\Http\Request;

// Import Form Request khusus untuk validasi data saat STORE (tambah anggota baru)
// Aturan validasi didefinisikan di app/Http/Requests/StoreAnggotaRequest.php
use App\Http\Requests\StoreAnggotaRequest;

// Import class Export untuk mengekspor data anggota ke format Excel (.xlsx)
// Class ini didefinisikan di app/Exports/AnggotaExport.php
use App\Exports\AnggotaExport;

// Import Facade Excel dari package maatwebsite/excel
// Facade ini menyediakan method download() dan store() untuk export/import Excel
use Maatwebsite\Excel\Facades\Excel;

// Import Form Request khusus untuk validasi data saat UPDATE (edit anggota)
// Aturan validasi didefinisikan di app/Http/Requests/UpdateAnggotaRequest.php
use App\Http\Requests\UpdateAnggotaRequest;

// ============================================================
// Class AnggotaController
// Extends Controller dasar Laravel yang menyediakan
// fitur middleware, authorize, dan dispatch.
// ============================================================
class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Method ini menampilkan halaman daftar seluruh anggota.
     * Fitur tambahan:
     *  - Mengembalikan (restore) filter pencarian dari session
     *  - Menghapus filter jika parameter clear_filter dikirim
     *  - Menghitung statistik: total, aktif, nonaktif
     */
    public function index(Request $request)
    {
        // -------------------------------------------------------
        // CLEAR FILTER
        // Jika URL mengandung parameter ?clear_filter, maka:
        //  1. Hapus session 'filter_anggota' agar filter sebelumnya tidak tersimpan lagi
        //  2. Redirect kembali ke halaman index tanpa parameter apapun
        // -------------------------------------------------------
        if ($request->has('clear_filter')) {
            session()->forget('filter_anggota');
            return redirect()->route('anggota.index');
        }

        // -------------------------------------------------------
        // RESTORE FILTER DARI SESSION
        // Jika session 'filter_anggota' ada DAN user tidak mengirim parameter baru,
        // maka redirect ke route search dengan parameter filter yang tersimpan.
        // Ini memastikan filter tetap aktif meskipun user menavigasi ke halaman lain
        // lalu kembali ke halaman anggota.
        // -------------------------------------------------------
        if (session()->has('filter_anggota') && empty($request->all())) {
            return redirect()->route('anggota.search', session('filter_anggota'));
        }

        // -------------------------------------------------------
        // AMBIL SEMUA DATA ANGGOTA
        // Menggunakan orderBy('created_at', 'desc') agar anggota terbaru
        // muncul di paling atas. get() mengembalikan Collection.
        // -------------------------------------------------------
        $anggotas = Anggota::orderBy('created_at', 'desc')->get();

        // -------------------------------------------------------
        // STATISTIK ANGGOTA
        // Menghitung jumlah total anggota, anggota aktif, dan nonaktif
        // untuk ditampilkan di dashboard/card statistik pada view.
        // -------------------------------------------------------
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();

        // Kirim data ke view 'anggota.index' menggunakan compact()
        // compact() membuat array asosiatif dari nama variabel
        return view('anggota.index', compact('anggotas', 'totalAnggota', 'anggotaAktif', 'anggotaNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * Method ini menampilkan form tambah anggota baru.
     * Kode anggota di-generate otomatis menggunakan helper generateKodeAnggota()
     * sehingga user tidak perlu input manual.
     */
    public function create()
    {
        // Generate kode anggota otomatis (format: AGT-YYYY-NNN)
        // menggunakan private method generateKodeAnggota()
        $kodeAnggota = $this->generateKodeAnggota();

        // Kirim kode anggota ke view form create agar langsung terisi di field
        return view('anggota.create', compact('kodeAnggota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Method ini menyimpan data anggota baru ke database.
     * Menggunakan StoreAnggotaRequest untuk validasi input secara otomatis.
     * Jika validasi gagal, Laravel akan redirect kembali dengan pesan error.
     * Jika validasi berhasil, data disimpan dengan try-catch untuk menangkap
     * kemungkinan error database (duplicate key, constraint violation, dll).
     */
    public function store(StoreAnggotaRequest $request)
    {
        try {
            // Buat record anggota baru menggunakan mass assignment
            // $request->validated() hanya mengembalikan field yang lolos validasi
            // sehingga aman dari mass assignment vulnerability
            Anggota::create($request->validated());

            // Redirect ke halaman daftar anggota dengan flash message sukses
            return redirect()->route('anggota.index')
                ->with('success', 'Anggota berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Jika terjadi error (misal: koneksi database gagal),
            // redirect kembali ke form dengan:
            //  - withInput(): mengembalikan input sebelumnya agar user tidak perlu isi ulang
            //  - with('error'): flash message error beserta detail exception
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * Method ini menampilkan detail satu anggota beserta riwayat transaksi pinjaman.
     * Fitur:
     *  - findOrFail: otomatis return 404 jika anggota tidak ditemukan
     *  - Eager loading relasi 'buku' pada transaksi untuk menghindari N+1 query
     *  - Filter transaksi berdasarkan status (Dipinjam, Dikembalikan, dll)
     *  - Statistik: total pinjaman dan total denda anggota
     */
    public function show(Request $request, string $id)
    {
        // Cari anggota berdasarkan ID, otomatis throw 404 jika tidak ditemukan
        $anggota = Anggota::findOrFail($id);

        // -------------------------------------------------------
        // RIWAYAT TRANSAKSI DENGAN EAGER LOADING
        // - transaksis(): relasi hasMany ke model Transaksi
        // - with('buku'): eager load relasi buku agar tidak terjadi N+1 query
        //   (setiap transaksi punya relasi ke buku yang dipinjam)
        // - latest(): urutkan dari transaksi terbaru
        // -------------------------------------------------------
        $query = $anggota->transaksis()->with('buku')->latest();
        
        // -------------------------------------------------------
        // FILTER TRANSAKSI BERDASARKAN STATUS
        // Jika parameter 'status' terisi di request (misal: ?status=Dipinjam),
        // tambahkan where clause untuk memfilter transaksi berdasarkan status.
        // Method filled() memastikan parameter ada dan tidak kosong.
        // -------------------------------------------------------
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Eksekusi query dan ambil hasilnya sebagai Collection
        $transaksis = $query->get();

        // -------------------------------------------------------
        // STATISTIK TRANSAKSI ANGGOTA
        // totalPinjam: jumlah total transaksi pinjaman yang pernah dilakukan
        // totalDenda: jumlah total denda yang dikenakan (sum kolom 'denda')
        // -------------------------------------------------------
        $totalPinjam = $anggota->transaksis()->count();
        $totalDenda = $anggota->transaksis()->sum('denda');

        // Kirim semua data ke view detail anggota
        return view('anggota.show', compact('anggota', 'transaksis', 'totalPinjam', 'totalDenda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Method ini menampilkan form edit anggota yang sudah terisi data existing.
     * findOrFail() memastikan anggota ada, jika tidak akan return 404.
     */
    public function edit(string $id)
    {
        // Cari anggota berdasarkan ID, throw 404 jika tidak ditemukan
        $anggota = Anggota::findOrFail($id);

        // Kirim data anggota ke view form edit agar field terisi otomatis
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Method ini mengupdate data anggota yang sudah ada di database.
     * Menggunakan UpdateAnggotaRequest untuk validasi (bisa berbeda dari store,
     * misalnya: email unique kecuali milik anggota ini sendiri).
     * Dibungkus try-catch untuk menangani error database.
     */
    public function update(UpdateAnggotaRequest $request, string $id)
    {
        try {
            // Cari anggota berdasarkan ID, throw 404 jika tidak ditemukan
            $anggota = Anggota::findOrFail($id);

            // Update kolom-kolom anggota dengan data yang sudah divalidasi
            // Hanya field yang lolos validasi yang akan di-update
            $anggota->update($request->validated());

            // Redirect ke halaman detail anggota dengan flash message sukses
            return redirect()->route('anggota.show', $anggota->id)->with('success', 'Data anggota berhasil diupdate!');
        } catch (\Exception $e) {
            // Jika gagal update (misal: constraint violation),
            // redirect kembali ke form edit dengan input sebelumnya dan pesan error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate anggota: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * Method ini menghapus data anggota dari database.
     * Nama anggota disimpan dulu sebelum dihapus agar bisa ditampilkan
     * di flash message konfirmasi penghapusan.
     * Dibungkus try-catch untuk menangani error (misal: foreign key constraint
     * jika anggota masih punya transaksi aktif).
     */
    public function destroy(string $id)
    {
        try {
            // Cari anggota berdasarkan ID, throw 404 jika tidak ditemukan
            $anggota = Anggota::findOrFail($id);

            // Simpan nama anggota sebelum dihapus untuk ditampilkan di pesan sukses
            $namaAnggota = $anggota->nama;

            // Hapus record anggota dari database
            // Jika ada relasi onDelete('cascade'), data terkait juga akan terhapus
            $anggota->delete();

            // Redirect ke halaman daftar anggota dengan pesan sukses
            return redirect()->route('anggota.index')
                 ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus!");
        } catch (\Exception $e) {
            // Jika gagal hapus (misal: foreign key constraint masih ada),
            // redirect kembali dengan pesan error
            return redirect()->back()
                ->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    /**
     * Export data anggota ke Excel.
     *
     * Method ini menggunakan Facade Excel dari package maatwebsite/excel
     * untuk men-download file Excel (.xlsx) berisi data seluruh anggota.
     * Nama file diberi timestamp agar unik setiap kali diunduh.
     * Format nama file: anggota_YYYY-MM-DD_HHmmss.xlsx
     */
    public function export()
    {
        // Download file Excel menggunakan class AnggotaExport sebagai data source
        // Nama file diberi prefix 'anggota_' + timestamp untuk keunikan
        return Excel::download(new AnggotaExport, 'anggota_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Advanced Search & Filter Anggota.
     *
     * Method ini melakukan pencarian dan filter anggota berdasarkan
     * beberapa kriteria: keyword, jenis kelamin, status, pekerjaan,
     * dan range umur. Menggunakan Query Builder pattern untuk
     * membangun query secara dinamis berdasarkan parameter yang dikirim.
     * Filter yang digunakan disimpan ke session agar bisa di-restore
     * saat user kembali ke halaman anggota.
     */
    public function search(Request $request)
    {
        // Inisialisasi query builder dari model Anggota
        // Anggota::query() mengembalikan query builder tanpa constraint apapun
        $query = Anggota::query();
        
        // -------------------------------------------------------
        // FILTER 1: KEYWORD (Pencarian Umum)
        // Cari keyword di kolom nama, email, atau telepon sekaligus.
        // Menggunakan closure where + orWhere agar kondisi OR terbungkus
        // dalam satu grup (tidak mengganggu filter lain yang pakai AND).
        // Contoh: WHERE (nama LIKE '%keyword%' OR email LIKE '%keyword%' OR telepon LIKE '%keyword%')
        // -------------------------------------------------------
        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('telepon', 'like', '%' . $request->keyword . '%');
            });
        }
        
        // -------------------------------------------------------
        // FILTER 2: JENIS KELAMIN
        // Filter exact match berdasarkan jenis kelamin (Laki-laki / Perempuan)
        // -------------------------------------------------------
        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        // -------------------------------------------------------
        // FILTER 3: STATUS KEANGGOTAAN
        // Filter exact match berdasarkan status (Aktif / Nonaktif)
        // -------------------------------------------------------
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // -------------------------------------------------------
        // FILTER 4: PEKERJAAN
        // Filter exact match berdasarkan pekerjaan
        // (Mahasiswa, Dosen, Karyawan, dll)
        // -------------------------------------------------------
        if ($request->pekerjaan) {
            $query->where('pekerjaan', $request->pekerjaan);
        }
        
        // -------------------------------------------------------
        // FILTER 5: RANGE UMUR (MINIMUM)
        // Menghitung tanggal maksimal lahir berdasarkan umur minimum.
        // Logika: jika min_umur = 20, maka tanggal lahir harus <= (sekarang - 20 tahun)
        // Artinya yang lahir 20 tahun lalu atau lebih lama = umur >= 20
        // Contoh: min_umur=20, sekarang 2026 → max_date = 2006-07-07
        //         Anggota lahir sebelum/pada tanggal itu = umur >= 20 tahun
        // -------------------------------------------------------
        if ($request->min_umur) {
            $max_date = now()->subYears($request->min_umur)->format('Y-m-d');
            $query->where('tanggal_lahir', '<=', $max_date);
        }

        // -------------------------------------------------------
        // FILTER 6: RANGE UMUR (MAKSIMUM)
        // Menghitung tanggal minimal lahir berdasarkan umur maksimum.
        // Logika: max_umur=25 → orang tersebut lahir paling lama (25+1) tahun lalu
        // Ditambah 1 tahun dan pakai operator '>' untuk presisi batas umur.
        // Contoh: max_umur=25, sekarang 2026 → min_date = 2000-07-07
        //         Anggota lahir setelah tanggal itu = umur <= 25 tahun
        // -------------------------------------------------------
        if ($request->max_umur) {
            // max umur 25 means they were born at least 25 years ago
            $min_date = now()->subYears($request->max_umur + 1)->format('Y-m-d');
            $query->where('tanggal_lahir', '>', $min_date);
        }

        // -------------------------------------------------------
        // SIMPAN FILTER KE SESSION
        // Jika salah satu filter terisi (anyFilled), simpan semua parameter
        // ke session 'filter_anggota'. Ini digunakan oleh method index()
        // untuk me-restore filter saat user kembali ke halaman anggota.
        // -------------------------------------------------------
        if ($request->anyFilled(['keyword', 'jenis_kelamin', 'status', 'pekerjaan', 'min_umur', 'max_umur'])) {
            session(['filter_anggota' => $request->all()]);
        }

        // Eksekusi query dengan urutan terbaru (latest = orderBy created_at desc)
        $anggotas = $query->latest()->get();
        
        // -------------------------------------------------------
        // STATISTIK HASIL PENCARIAN
        // Dihitung dari hasil filter (bukan seluruh data),
        // sehingga statistik mencerminkan hasil pencarian saat ini.
        // Menggunakan method Collection (bukan query builder) karena
        // data sudah di-fetch ke memory dengan get().
        // -------------------------------------------------------
        $totalAnggota = $anggotas->count();
        $anggotaAktif = $anggotas->where('status', 'Aktif')->count();
        $anggotaNonaktif = $anggotas->where('status', 'Nonaktif')->count();
        
        // Kirim hasil pencarian dan statistik ke view yang sama (anggota.index)
        // View akan menampilkan data sesuai hasil filter
        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

    /**
     * Helper function untuk auto-generate kode anggota.
     *
     * Private method ini membuat kode anggota unik secara otomatis
     * dengan format: AGT-YYYY-NNN
     *  - AGT   : prefix tetap (singkatan Anggota)
     *  - YYYY  : tahun saat ini (misal: 2026)
     *  - NNN   : nomor urut 3 digit dengan zero-padding (001, 002, ..., 999)
     *
     * Cara kerja:
     *  1. Ambil tahun saat ini
     *  2. Cari anggota terakhir yang dibuat di tahun ini (whereYear)
     *  3. Ambil 3 digit terakhir dari kode anggota terakhir (substr)
     *  4. Tambahkan 1 untuk nomor urut baru
     *  5. Jika belum ada anggota di tahun ini, mulai dari 001
     *  6. Gabungkan prefix + tahun + nomor urut dengan zero-padding (str_pad)
     */
    private function generateKodeAnggota()
    {
        // Ambil tahun saat ini untuk prefix kode
        $tahun = date('Y');

        // Cari anggota terakhir yang dibuat di tahun ini
        // whereYear() memfilter berdasarkan tahun dari kolom created_at
        // orderBy('kode_anggota', 'desc') agar mendapat kode terbesar
        // first() ambil 1 record pertama (yang kodenya paling besar)
        $lastAnggota = Anggota::whereYear('created_at', $tahun)
                              ->orderBy('kode_anggota', 'desc')
                              ->first();
        
        if ($lastAnggota) {
            // Jika sudah ada anggota di tahun ini:
            // substr($kode, -3) → ambil 3 karakter terakhir (nomor urut)
            // intval() → konversi string ke integer (misal: "005" → 5)
            // Tambah 1 untuk nomor urut berikutnya
            $lastNumber = intval(substr($lastAnggota->kode_anggota, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada anggota di tahun ini, mulai dari nomor 1
            $newNumber = 1;
        }
        
        // Gabungkan format kode: AGT-YYYY-NNN
        // str_pad($newNumber, 3, '0', STR_PAD_LEFT) → padding kiri dengan '0' hingga 3 digit
        // Contoh: 1 → "001", 12 → "012", 123 → "123"
        return 'AGT-' . $tahun . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}