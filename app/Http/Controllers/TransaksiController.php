<?php

// ============================================================
// FILE: TransaksiController.php
// FUNGSI: Mengelola transaksi peminjaman & pengembalian buku,
//         laporan transaksi, dan export PDF
// ROUTE: Resource route → /transaksi + custom routes
// ============================================================

// Namespace controller, sesuai struktur folder Laravel app/Http/Controllers
namespace App\Http\Controllers;

// Mengimpor class Request untuk menangkap data dari HTTP request (query string, form data, dsb.)
use Illuminate\Http\Request;

// Mengimpor model Transaksi untuk operasi CRUD pada tabel transaksi
use App\Models\Transaksi;

// Mengimpor model Buku untuk mengakses dan memanipulasi data buku (cek stok, increment/decrement)
use App\Models\Buku;

// Mengimpor model Anggota untuk mengakses data anggota perpustakaan (cek status aktif, dsb.)
use App\Models\Anggota;

// Mengimpor facade DB untuk menjalankan database transaction (operasi atomik agar data konsisten)
use Illuminate\Support\Facades\DB;

// Mengimpor library Carbon untuk manipulasi tanggal (addDays, diffInDays, parse, dsb.)
use Carbon\Carbon;

// Mengimpor facade PDF dari package barryvdh/laravel-dompdf untuk generate dan download file PDF
use Barryvdh\DomPDF\Facade\Pdf;

// ==========================
// Class TransaksiController
// Menangani semua logika bisnis terkait transaksi peminjaman buku
// ==========================
class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // -------------------------------------------------------
    // METHOD: index()
    // FUNGSI: Menampilkan daftar semua transaksi dengan filter
    //         Filter disimpan di session agar tetap aktif saat
    //         user berpindah halaman lalu kembali
    // -------------------------------------------------------
    public function index(Request $request)
    {
        // Jika user menekan tombol "clear filter", hapus session filter
        // lalu redirect ke halaman index tanpa parameter filter
        if ($request->has('clear_filter')) {
            session()->forget('filter_transaksi');
            return redirect()->route('transaksi.index');
        }

        // Jika request memiliki salah satu parameter filter yang terisi,
        // simpan semua parameter filter ke session agar persisten
        if ($request->anyFilled(['status', 'anggota_id', 'buku_id', 'tgl_mulai', 'tgl_selesai'])) {
            session(['filter_transaksi' => $request->all()]);
        // Jika tidak ada parameter di URL tapi session filter masih ada,
        // redirect ulang dengan parameter dari session (mengembalikan filter sebelumnya)
        } elseif (session()->has('filter_transaksi') && empty($request->all())) {
            return redirect()->route('transaksi.index', session('filter_transaksi'));
        }

        // Membuat query builder Transaksi dengan eager loading relasi 'anggota' dan 'buku'
        // Eager loading mencegah N+1 query problem saat menampilkan data relasi
        $query = Transaksi::with(['anggota', 'buku']);

        // Filter berdasarkan status transaksi (Dipinjam / Dikembalikan)
        if ($request->status) {
            $query->where('status', $request->status);
        }
        // Filter berdasarkan ID anggota tertentu
        if ($request->anggota_id) {
            $query->where('anggota_id', $request->anggota_id);
        }
        // Filter berdasarkan ID buku tertentu
        if ($request->buku_id) {
            $query->where('buku_id', $request->buku_id);
        }
        // Filter tanggal pinjam mulai dari (>=) tanggal yang ditentukan
        if ($request->tgl_mulai) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        }
        // Filter tanggal pinjam sampai dengan (<=) tanggal yang ditentukan
        if ($request->tgl_selesai) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        // Eksekusi query: urutkan dari terbaru (latest) dan ambil semua hasil
        $transaksis = $query->latest()->get();

        // Ambil semua data anggota (untuk dropdown filter), diurutkan berdasarkan nama
        $anggotas = Anggota::orderBy('nama')->get();

        // Ambil semua data buku (untuk dropdown filter), diurutkan berdasarkan judul
        $bukus = Buku::orderBy('judul')->get();

        // Kirim data ke view 'transaksi.index' menggunakan compact
        return view('transaksi.index', compact('transaksis', 'anggotas', 'bukus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // -------------------------------------------------------
    // METHOD: create()
    // FUNGSI: Menampilkan form pembuatan transaksi baru
    //         Hanya menampilkan anggota aktif dan buku tersedia
    //         agar user tidak bisa memilih data yang tidak valid
    // -------------------------------------------------------
    public function create()
    {
        // Get only anggota aktif
        // Hanya anggota dengan status 'Aktif' yang boleh meminjam buku
        // Anggota non-aktif (misalnya diblokir/kadaluarsa) tidak ditampilkan di dropdown
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();

        // Get only buku yang tersedia (stok > 0)
        // Hanya buku yang stoknya masih ada (> 0) yang ditampilkan di dropdown
        // Buku dengan stok habis tidak bisa dipinjam
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        // Kirim data anggota dan buku ke view form create
        return view('transaksi.create', compact('anggotas', 'bukus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // -------------------------------------------------------
    // METHOD: store()
    // FUNGSI: Menyimpan transaksi peminjaman baru ke database
    //         Menggunakan DB::transaction agar semua operasi
    //         (validasi, create, decrement stok) bersifat ATOMIK
    //         → jika salah satu gagal, semua di-rollback
    // -------------------------------------------------------
    public function store(Request $request)
    {
        // Validasi input dari form:
        // - anggota_id: wajib diisi dan harus ada di tabel anggota
        // - buku_id: wajib diisi dan harus ada di tabel buku
        // - tanggal_pinjam: wajib diisi dan harus format tanggal valid
        // - keterangan: opsional, bertipe string
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            // Pesan error custom dalam Bahasa Indonesia
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);

        try {
            // DB::transaction memastikan semua operasi di dalam closure bersifat atomik
            // Jika ada exception/throw, semua perubahan database di-rollback otomatis
            DB::transaction(function () use ($request) {
                // ===== VALIDASI BISNIS 1: Cek status anggota =====
                // Anggota harus berstatus 'Aktif' untuk bisa meminjam buku
                // findOrFail akan throw 404 jika anggota tidak ditemukan
                // 1. Cek status anggota (harus Aktif)
                $anggota = Anggota::findOrFail($request->anggota_id);
                if ($anggota->status !== 'Aktif') {
                    throw new \Exception('Anggota tersebut tidak aktif!');
                }

                // ===== VALIDASI BISNIS 2: Cek duplikasi peminjaman =====
                // Satu anggota tidak boleh meminjam buku yang SAMA jika masih berstatus 'Dipinjam'
                // Mencegah peminjaman ganda untuk buku yang belum dikembalikan
                // 2. Cek apakah anggota sedang meminjam buku ini (belum dikembalikan)
                $existingTransaction = Transaksi::where('anggota_id', $request->anggota_id)
                    ->where('buku_id', $request->buku_id)
                    ->where('status', 'Dipinjam')
                    ->first();
                if ($existingTransaction) {
                    throw new \Exception('Anggota ini sedang meminjam buku ini dan belum mengembalikannya!');
                }

                // ===== VALIDASI BISNIS 3: Batas maksimal peminjaman =====
                // Setiap anggota hanya boleh meminjam maksimal 3 buku secara bersamaan
                // Menghitung jumlah transaksi aktif (status 'Dipinjam') untuk anggota ini
                // 3. Batasi peminjaman aktif maksimal 3 buku per anggota
                $borrowedCount = Transaksi::where('anggota_id', $request->anggota_id)
                    ->where('status', 'Dipinjam')
                    ->count();
                if ($borrowedCount >= 3) {
                    throw new \Exception('Anggota telah mencapai batas maksimal peminjaman (maksimal 3 buku)!');
                }

                // ===== VALIDASI BISNIS 4: Cek ketersediaan stok buku =====
                // Stok buku harus lebih dari 0 agar bisa dipinjam
                // findOrFail akan throw 404 jika buku tidak ditemukan
                // 4. Check stok buku
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }

                // ===== GENERATE KODE TRANSAKSI =====
                // Membuat kode transaksi unik secara otomatis (format: TRX-001, TRX-002, dst.)
                // 2. Generate kode transaksi
                $kodeTransaksi = $this->generateKodeTransaksi();

                // ===== HITUNG TANGGAL KEMBALI =====
                // Tanggal kembali = tanggal pinjam + 7 hari
                // Menggunakan Carbon::parse() untuk mengubah string tanggal menjadi objek Carbon
                // addDays(7) menambahkan 7 hari dari tanggal pinjam
                // 3. Calculate tanggal kembali (7 hari dari tanggal pinjam)
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);

                // ===== SIMPAN DATA TRANSAKSI KE DATABASE =====
                // Membuat record baru di tabel transaksi dengan status awal 'Dipinjam'
                // 4. Create transaksi
                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id' => $request->anggota_id,
                    'buku_id' => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => 'Dipinjam',
                    'keterangan' => $request->keterangan,
                ]);

                // ===== KURANGI STOK BUKU =====
                // Setelah transaksi berhasil dibuat, kurangi stok buku sebanyak 1
                // Menggunakan decrement() agar query UPDATE langsung di database (thread-safe)
                // 5. Update stok buku (kurang 1)
                $buku->decrement('stok');
            });

            // Jika semua operasi dalam transaction berhasil, redirect ke halaman index
            // dengan pesan sukses
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi peminjaman berhasil dibuat!');
        } catch (\Exception $e) {
            // Jika terjadi error (termasuk throw dari validasi bisnis),
            // redirect kembali ke form dengan input sebelumnya dan pesan error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    // -------------------------------------------------------
    // METHOD: show()
    // FUNGSI: Menampilkan detail satu transaksi berdasarkan ID
    //         Menggunakan eager loading untuk memuat relasi
    //         'anggota' dan 'buku' dalam satu query
    // -------------------------------------------------------
    public function show(string $id)
    {
        // Cari transaksi berdasarkan ID dengan eager loading relasi anggota dan buku
        // findOrFail akan throw 404 jika transaksi tidak ditemukan
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);

        // Kirim data transaksi ke view detail
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Kembalikan buku (update status transaksi).
     */
    // -------------------------------------------------------
    // METHOD: kembalikan()
    // FUNGSI: Memproses pengembalian buku oleh anggota
    //         - Mengubah status transaksi menjadi 'Dikembalikan'
    //         - Mencatat tanggal dikembalikan
    //         - Menghitung denda jika terlambat
    //         - Mengembalikan stok buku (+1)
    //         Dibungkus DB::transaction agar atomik
    // -------------------------------------------------------
    public function kembalikan(string $id)
    {
        try {
            // DB::transaction memastikan update status, pencatatan denda,
            // dan penambahan stok buku dilakukan secara atomik
            DB::transaction(function () use ($id) {
                // Cari transaksi berdasarkan ID, throw 404 jika tidak ditemukan
                $transaksi = Transaksi::findOrFail($id);

                // Cek apakah sudah dikembalikan
                // Validasi: jika buku sudah berstatus 'Dikembalikan', tolak proses
                // Mencegah pengembalian ganda (double return)
                if ($transaksi->status === 'Dikembalikan') {
                    throw new \Exception('Buku sudah dikembalikan sebelumnya.');
                }

                // Catat waktu pengembalian saat ini menggunakan helper now()
                $tanggalDikembalikan = now();

                // Hitung denda keterlambatan berdasarkan selisih tanggal kembali
                // dan tanggal dikembalikan (Rp 5.000 per hari keterlambatan)
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                // Update data transaksi:
                // - status → 'Dikembalikan'
                // - tanggal_dikembalikan → waktu saat ini
                // - denda → hasil perhitungan denda (0 jika tidak terlambat)
                $transaksi->update([
                    'status' => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda' => $denda,
                ]);

                // Tambahkan kembali stok buku sebanyak 1 setelah buku dikembalikan
                // increment() menjalankan UPDATE langsung di database (thread-safe)
                $transaksi->buku->increment('stok');
            });

            // Redirect ke halaman detail transaksi dengan pesan sukses
            return redirect()->route('transaksi.show', $id) ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali dengan pesan error
            return redirect()->back() ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }



    /**
     * Generate kode transaksi otomatis.
     */
    // -------------------------------------------------------
    // METHOD: generateKodeTransaksi() [private]
    // FUNGSI: Membuat kode transaksi unik secara auto-increment
    //         Format: TRX-001, TRX-002, TRX-003, dst.
    //         Mengambil nomor terakhir dari database lalu +1
    // -------------------------------------------------------
    private function generateKodeTransaksi()
    {
        // Ambil transaksi terakhir berdasarkan ID terbesar
        $lastTransaksi = Transaksi::orderBy('id', 'desc')->first();

        if ($lastTransaksi) {
            // Jika sudah ada transaksi sebelumnya:
            // Ambil 3 digit terakhir dari kode_transaksi (misal: TRX-005 → '005')
            // Konversi ke integer (005 → 5), lalu tambahkan 1 (5 → 6)
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada transaksi sama sekali, mulai dari nomor 1
            $newNumber = 1;
        }

        // Format kode: 'TRX-' + nomor 3 digit dengan zero-padding (str_pad)
        // Contoh: 1 → 'TRX-001', 25 → 'TRX-025', 100 → 'TRX-100'
        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung denda keterlambatan.
     */
    // -------------------------------------------------------
    // METHOD: hitungDenda() [private]
    // FUNGSI: Menghitung denda keterlambatan pengembalian buku
    //         Tarif: Rp 5.000 per hari keterlambatan
    //         Jika dikembalikan tepat waktu atau lebih awal → denda = 0
    // -------------------------------------------------------
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        // Hitung selisih hari antara tanggal_kembali (batas) dan tanggal dikembalikan
        // Parameter 'false' pada diffInDays memungkinkan hasil negatif
        // Jika positif → terlambat, jika negatif/nol → tepat waktu atau lebih awal
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);

        // Jika terlambat (hari > 0), kenakan denda Rp 5.000 per hari
        if ($hariTerlambat > 0) {
            // Denda Rp 5.000 per hari
            // Contoh: 3 hari terlambat → 3 × 5000 = Rp 15.000
            return $hariTerlambat * 5000;
        }

        // Jika tidak terlambat (tepat waktu atau lebih awal), denda = 0
        return 0;
    }

    /**
     * Menampilkan laporan transaksi dengan filter.
     */
    // -------------------------------------------------------
    // METHOD: laporan()
    // FUNGSI: Menampilkan halaman laporan transaksi dengan
    //         filter berdasarkan rentang tanggal, status, dan
    //         anggota. Juga menghitung total transaksi & denda
    // -------------------------------------------------------
    public function laporan(Request $request)
    {
        // Ambil semua data anggota untuk dropdown filter di halaman laporan
        $anggotas = Anggota::orderBy('nama')->get();

        // Buat query builder dengan eager loading relasi buku dan anggota
        $query = Transaksi::with(['buku', 'anggota']);

        // Filter Tanggal Pinjam
        // Jika kedua tanggal (mulai & selesai) diisi → gunakan whereBetween untuk rentang tanggal
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        // Jika hanya tanggal mulai yang diisi → tampilkan transaksi dari tanggal tersebut ke atas
        } elseif ($request->filled('tgl_mulai')) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        // Jika hanya tanggal selesai yang diisi → tampilkan transaksi sampai tanggal tersebut
        } elseif ($request->filled('tgl_selesai')) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter Status
        // Jika status diisi dan bukan 'Semua', filter berdasarkan status yang dipilih
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter Anggota
        // Jika anggota_id diisi, filter transaksi untuk anggota tertentu saja
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        // Eksekusi query: ambil semua data terurut dari yang terbaru
        $transaksis = $query->latest()->get();

        // Hitung total jumlah transaksi yang sesuai filter
        $totalTransaksi = $transaksis->count();

        // Hitung total denda dari semua transaksi yang sesuai filter
        $totalDenda = $transaksis->sum('denda');

        // Kirim data ke view 'transaksi.laporan' untuk ditampilkan
        return view('transaksi.laporan', compact(
            'transaksis',
            'anggotas',
            'totalTransaksi',
            'totalDenda'
        ));
    }

    /**
     * Export laporan transaksi ke PDF murni server-side.
     */
    // -------------------------------------------------------
    // METHOD: exportPdf()
    // FUNGSI: Mengekspor data transaksi ke file PDF
    //         Menggunakan DomPDF untuk merender view Blade
    //         menjadi file PDF yang bisa didownload user
    //         Filter yang sama dengan laporan() diterapkan
    // -------------------------------------------------------
    public function exportPdf(Request $request)
    {
        // Buat query builder dengan eager loading relasi buku dan anggota
        $query = Transaksi::with(['buku', 'anggota']);

        // Filter Tanggal Pinjam
        // Logika filter sama persis dengan method laporan()
        // whereBetween digunakan jika kedua tanggal diisi
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_pinjam', [$request->tgl_mulai, $request->tgl_selesai]);
        // Jika hanya tanggal mulai → filter dari tanggal tersebut ke atas
        } elseif ($request->filled('tgl_mulai')) {
            $query->where('tanggal_pinjam', '>=', $request->tgl_mulai);
        // Jika hanya tanggal selesai → filter sampai tanggal tersebut
        } elseif ($request->filled('tgl_selesai')) {
            $query->where('tanggal_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter Status
        // Jika status diisi dan bukan 'Semua', filter berdasarkan status yang dipilih
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter Anggota
        // Jika anggota_id diisi, filter transaksi untuk anggota tertentu saja
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        // Eksekusi query: ambil semua data terurut dari yang terbaru
        $transaksis = $query->latest()->get();

        // Hitung statistik ringkasan untuk ditampilkan di PDF
        $totalTransaksi = $transaksis->count();         // Jumlah total transaksi
        $totalDenda = $transaksis->sum('denda');         // Total denda keseluruhan
        $totalDipinjam = $transaksis->where('status', 'Dipinjam')->count();       // Jumlah buku yang masih dipinjam
        $totalDikembalikan = $transaksis->where('status', 'Dikembalikan')->count(); // Jumlah buku yang sudah dikembalikan

        // Render view 'transaksi.pdf' menjadi dokumen PDF menggunakan DomPDF
        // loadView() memuat template Blade dan mengubahnya menjadi format PDF
        $pdf = Pdf::loadView('transaksi.pdf', compact(
            'transaksis',
            'totalTransaksi',
            'totalDenda',
            'totalDipinjam',
            'totalDikembalikan'
        ));

        // Format nama file: laporan_transaksi_20240101_101500.pdf
        // download() mengirimkan file PDF ke browser untuk diunduh oleh user
        // Nama file menggunakan format tanggal-waktu agar unik setiap kali diexport
        return $pdf->download('laporan_transaksi_' . date('Ymd_His') . '.pdf');
    }
}
