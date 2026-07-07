<?php

// ============================================================
// FILE: BukuController.php
// FUNGSI: Mengelola CRUD data Buku, Advanced Search & Filter,
//         Export Excel, Bulk Delete, dan Filter Kategori
// ROUTE: Resource route → /buku + custom routes
// ============================================================

// Namespace controller ini, mengikuti struktur folder Laravel
namespace App\Http\Controllers;

// ---------- USE STATEMENTS ----------

// Mengimpor Form Request khusus untuk validasi saat menambah buku baru
// Class ini berisi rules validasi (judul wajib diisi, harga numerik, dll)
use App\Http\Requests\StoreBukuRequest;

// Mengimpor Model Buku (Eloquent) untuk berinteraksi dengan tabel 'bukus' di database
// Digunakan di seluruh method controller ini untuk query data buku
use App\Models\Buku;

// Mengimpor class Request bawaan Laravel untuk menangkap input HTTP
// Digunakan pada method yang tidak memerlukan Form Request khusus (search, bulkDelete, index)
use Illuminate\Http\Request;

// Mengimpor Form Request khusus untuk validasi saat mengupdate buku
// Class ini berisi rules validasi yang mungkin berbeda dari StoreBukuRequest
use App\Http\Requests\UpdateBukuRequest;

// Mengimpor Model Kategori (Eloquent) untuk berinteraksi dengan tabel 'kategoris'
// Digunakan untuk mengambil data dropdown kategori di form dan halaman index
use App\Models\Kategori;

// Mengimpor class BukuExport yang mengimplementasikan FromCollection/FromQuery
// Class ini mendefinisikan data apa saja yang akan diekspor ke file Excel
use App\Exports\BukuExport;

// Mengimpor Facade Excel dari package maatwebsite/excel
// Facade ini menyediakan method download() untuk mengunduh file Excel
use Maatwebsite\Excel\Facades\Excel;

// ============================================================
// CLASS: BukuController
// Extends Controller bawaan Laravel (App\Http\Controllers\Controller)
// Menangani semua operasi terkait data Buku
// ============================================================
class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * METHOD: index()
     * ROUTE : GET /buku (buku.index)
     * FUNGSI: Menampilkan halaman utama daftar buku beserta statistik
     *         dan data dropdown untuk filter.
     *         Mendukung session-based filter persistence.
     *
     * @param  Request $request - Object HTTP request dari browser
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // Jika ada clear_filter, hapus session dan load semua
        // Cek apakah URL mengandung parameter ?clear_filter
        // Jika ya, hapus session 'filter_buku' agar filter direset
        if ($request->has('clear_filter')) {
            session()->forget('filter_buku');
            // Redirect kembali ke halaman index tanpa parameter apapun
            return redirect()->route('buku.index');
        }

        // Restore filter preferences from session if exist
        // Jika session 'filter_buku' ada DAN user tidak mengirim parameter apapun,
        // maka otomatis redirect ke route search dengan filter yang tersimpan di session.
        // Ini membuat filter tetap aktif meskipun user menavigasi ke halaman lain lalu kembali.
        if (session()->has('filter_buku') && empty($request->all())) {
            return redirect()->route('buku.search', session('filter_buku'));
        }

        // Ambil semua data buku dari database
        // latest() = ORDER BY created_at DESC (buku terbaru muncul duluan)
        // get() = eksekusi query dan kembalikan Collection
        $bukus = Buku::latest()->get();

        // Statistik untuk card di dashboard
        // count() = SELECT COUNT(*) FROM bukus
        $totalBuku = Buku::count();
        // Hitung buku yang stoknya lebih dari 0 (masih tersedia)
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        // Hitung buku yang stoknya tepat 0 (habis)
        $bukuHabis = Buku::where('stok', 0)->count();

        // Data untuk dropdown filter di halaman index
        // Ambil semua kategori, diurutkan berdasarkan nama kategori A-Z
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        // Ambil daftar tahun terbit yang unik (tanpa duplikat), diurutkan DESC
        // pluck() = hanya ambil satu kolom sebagai Collection, bukan seluruh row
        $tahuns = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        // Kirim semua variabel ke view 'buku.index' (resources/views/buku/index.blade.php)
        // compact() membuat array asosiatif dari nama variabel
        return view('buku.index', compact(
            'bukus', 'totalBuku', 'bukuTersedia', 'bukuHabis', 'kategoris', 'tahuns'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * METHOD: create()
     * ROUTE : GET /buku/create (buku.create)
     * FUNGSI: Menampilkan form untuk menambah buku baru.
     *         Menyediakan data kategori untuk dropdown di form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Ambil semua kategori untuk ditampilkan di dropdown form create
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        // Tampilkan view form create dengan data kategori
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * METHOD: store()
     * ROUTE : POST /buku (buku.store)
     * FUNGSI: Menyimpan data buku baru ke database.
     *         Validasi dilakukan otomatis oleh StoreBukuRequest sebelum masuk method ini.
     *         Jika validasi gagal, Laravel otomatis redirect kembali ke form dengan error.
     *
     * @param  StoreBukuRequest $request - Request yang sudah tervalidasi oleh Form Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            // $request->validated() mengembalikan array data yang sudah lolos validasi
            // Buku::create() melakukan INSERT INTO bukus (...) VALUES (...)
            Buku::create($request->validated());

            // Redirect dengan success message
            // with('success', ...) menyimpan flash message di session untuk ditampilkan sekali
            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            // back() = kembali ke halaman sebelumnya (form create)
            // withInput() = mengisi kembali form dengan data yang sudah diinput user
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * METHOD: show()
     * ROUTE : GET /buku/{id} (buku.show)
     * FUNGSI: Menampilkan detail satu buku berdasarkan ID.
     *         Jika ID tidak ditemukan, findOrFail() akan throw ModelNotFoundException
     *         dan Laravel otomatis menampilkan halaman 404.
     *
     * @param  string $id - ID buku dari URL parameter
     * @return \Illuminate\View\View
     */
    public function show(string $id)
    {
        // ........
        // findOrFail() = SELECT * FROM bukus WHERE id = ? LIMIT 1
        // Jika tidak ditemukan, otomatis throw 404 Not Found
        $buku = Buku::findOrFail($id);

        //........
        // Tampilkan view detail buku dengan data buku yang ditemukan
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * METHOD: edit()
     * ROUTE : GET /buku/{id}/edit (buku.edit)
     * FUNGSI: Menampilkan form edit untuk buku tertentu.
     *         Data buku yang sudah ada akan ditampilkan di form untuk diedit.
     *
     * @param  string $id - ID buku dari URL parameter
     * @return \Illuminate\View\View
     */
    public function edit(string $id)
    {
        // Cari buku berdasarkan ID, throw 404 jika tidak ditemukan
        $buku = Buku::findOrFail($id);
        // Ambil semua kategori untuk dropdown di form edit
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        // Tampilkan view form edit dengan data buku dan kategori
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     *
     * METHOD: update()
     * ROUTE : PUT/PATCH /buku/{id} (buku.update)
     * FUNGSI: Mengupdate data buku yang sudah ada di database.
     *         Validasi dilakukan otomatis oleh UpdateBukuRequest.
     *
     * @param  UpdateBukuRequest $request - Request yang sudah tervalidasi
     * @param  string $id - ID buku yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            // Cari buku berdasarkan ID, throw 404 jika tidak ditemukan
            $buku = Buku::findOrFail($id);

            // Update buku dengan validated data
            // update() melakukan UPDATE bukus SET ... WHERE id = ?
            // Hanya kolom yang ada di validated() yang akan diupdate
            $buku->update($request->validated());

            // Redirect dengan success message
            // Redirect ke halaman detail buku yang baru saja diupdate
            return redirect()->route('buku.show', $buku->id)->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            // back() = kembali ke form edit, withInput() = isi form dengan data sebelumnya
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * METHOD: destroy()
     * ROUTE : DELETE /buku/{id} (buku.destroy)
     * FUNGSI: Menghapus satu buku dari database berdasarkan ID.
     *         Menyimpan judul buku sebelum dihapus untuk ditampilkan di flash message.
     *
     * @param  string $id - ID buku yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            // Cari buku berdasarkan ID, throw 404 jika tidak ditemukan
            $buku = Buku::findOrFail($id);
            // Simpan judul buku sebelum dihapus, untuk ditampilkan di pesan sukses
            $judulBuku = $buku->judul;

            // Delete buku
            // delete() melakukan DELETE FROM bukus WHERE id = ?
            $buku->delete();

            // Redirect dengan success message
            // Kembali ke halaman index dengan pesan yang menyertakan judul buku yang dihapus
            return redirect()->route('buku.index')
                ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove multiple resources from storage.
     *
     * METHOD: bulkDelete()
     * ROUTE : POST /buku/bulk-delete (buku.bulkDelete) — custom route
     * FUNGSI: Menghapus beberapa buku sekaligus berdasarkan array ID yang dipilih user.
     *         ID dikirim dari form dengan checkbox, nama input: buku_ids[]
     *
     * @param  Request $request - Berisi array 'buku_ids' dari checkbox form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        try {
            // Ambil array ID buku yang dipilih user dari input form
            // Input berupa array, contoh: [1, 3, 5, 7]
            $ids = $request->input('buku_ids');

            // Validasi: jika tidak ada ID yang dipilih, kembalikan pesan error
            if (empty($ids)) {
                return redirect()->route('buku.index')
                    ->with('error', 'Silakan pilih buku yang ingin dihapus terlebih dahulu.');
            }

            // whereIn() = WHERE id IN (1, 3, 5, 7)
            // Menghapus semua buku yang ID-nya ada di dalam array $ids
            // Ini lebih efisien daripada menghapus satu per satu dalam loop
            // SQL equivalent: DELETE FROM bukus WHERE id IN (1, 3, 5, 7)
            Buku::whereIn('id', $ids)->delete();

            // Redirect ke halaman index dengan pesan sukses yang menyertakan jumlah buku yang dihapus
            // count($ids) menghitung berapa buku yang berhasil dihapus
            return redirect()->route('buku.index')
                ->with('success', count($ids) . ' buku berhasil dihapus!');
        } catch (\Exception $e) {
            // Jika terjadi error (misal: constraint foreign key), tampilkan pesan error
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku secara massal: ' . $e->getMessage());
        }
    }

    /**
     * Export data buku ke Excel.
     *
     * METHOD: export()
     * ROUTE : GET /buku/export (buku.export) — custom route
     * FUNGSI: Mengekspor seluruh data buku ke file Excel (.xlsx) untuk diunduh.
     *         Menggunakan package maatwebsite/excel dan class BukuExport.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse — response berupa file download
     */
    public function export()
    {
        // Excel::download() melakukan:
        // 1. Membuat instance BukuExport yang mengambil data buku dari database
        // 2. Mengkonversi data ke format Excel (.xlsx)
        // 3. Mengembalikan response HTTP berupa file download
        // Nama file: buku_2026-07-07_110306.xlsx (dengan timestamp saat diunduh)
        // date('Y-m-d_His') menghasilkan format tanggal-jam untuk nama file unik
        return Excel::download(new BukuExport, 'buku_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Filter buku berdasarkan kategori tertentu.
     *
     * METHOD: filterKategori()
     * ROUTE : GET /buku/kategori/{kategori_id} (buku.filterKategori) — custom route
     * FUNGSI: Menampilkan daftar buku yang difilter berdasarkan kategori tertentu.
     *         Method ini MENGGUNAKAN ULANG (reuse) view 'buku.index' yang sama
     *         dengan method index(), tetapi dengan data yang sudah difilter.
     *         Sehingga tampilan halaman tetap sama, hanya datanya yang berbeda.
     *
     * @param  int|string $kategori_id - ID kategori dari URL parameter
     * @return \Illuminate\View\View
     */
    public function filterKategori($kategori_id)
    {
        // Ambil buku yang kategori_id-nya sesuai parameter, diurutkan terbaru
        // SQL: SELECT * FROM bukus WHERE kategori_id = ? ORDER BY created_at DESC
        $bukus = Buku::where('kategori_id', $kategori_id)->latest()->get();

        // Hitung statistik hanya dari hasil filter (bukan seluruh database)
        // Ini membuat card statistik menampilkan angka yang relevan dengan filter
        $totalBuku = $bukus->count();
        // Method where() pada Collection (bukan Query Builder) untuk filter di memory
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();

        // Data untuk dropdown filter — tetap ambil semua kategori dan tahun
        // agar dropdown di halaman index tetap lengkap meskipun data difilter
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $tahuns = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        // Kirim data ke view 'buku.index' yang SAMA dengan method index()
        // View akan menampilkan data yang sudah difilter tanpa perlu view terpisah
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategoris',
            'tahuns'
        ));
    }

    /**
     * Search dan filter buku berdasarkan multiple kriteria
     * 
     * Method ini menerima input dari form search dan membangun query
     * secara dinamis berdasarkan filter yang diisi user
     *
     * METHOD: search()
     * ROUTE : GET /buku/search (buku.search) — custom route
     * FUNGSI: Advanced search & filter dengan multiple kriteria sekaligus.
     *         Membangun query Eloquent secara DINAMIS — hanya menambahkan
     *         klausa WHERE untuk filter yang benar-benar diisi oleh user.
     *         Mendukung: keyword, kategori, tahun, ketersediaan, range harga.
     *         Menyimpan preferensi filter ke session agar persisten.
     * 
     * @param Request $request - Object request berisi input dari form
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // ========== INISIALISASI QUERY BUILDER ==========

        // Membuat query builder instance
        // query() mengembalikan Eloquent Builder, bukan hasil query
        // Ini adalah titik awal untuk menyusun query secara dinamis
        // Belum ada SQL yang dieksekusi di sini, hanya mempersiapkan builder
        $query = Buku::query();


        // ========== FILTER KEYWORD (SEARCH) ==========

        // Ambil input keyword dari form (input name="keyword")
        $keyword = $request->input('keyword');

        // Jika keyword diisi, cari di 3 kolom: judul, pengarang, penerbit
        if ($keyword) {
            // where() dengan closure untuk grouping kondisi OR
            // Closure (function) menciptakan GROUP dengan tanda kurung di SQL
            // Tanpa closure, kondisi OR bisa mengganggu filter lainnya
            $query->where(function ($q) use ($keyword) {
                // LIKE '%keyword%' = mencari substring di kolom
                // '%' di awal dan akhir = keyword bisa muncul di mana saja dalam string
                $q->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('pengarang', 'like', "%{$keyword}%")
                    ->orWhere('penerbit', 'like', "%{$keyword}%");
            });

            // Query SQL yang dihasilkan:
            // WHERE (judul LIKE '%keyword%' OR pengarang LIKE '%keyword%' OR penerbit LIKE '%keyword%')
        }


        // ========== FILTER KATEGORI ==========

        // Ambil input kategori dari dropdown (input name="kategori_id")
        $kategori_id = $request->input('kategori_id');

        // Jika kategori dipilih (bukan "Semua")
        // Nilai kosong/null dianggap falsy, sehingga filter tidak diterapkan
        if ($kategori_id) {
            // Tambahkan kondisi WHERE kategori_id = value
            // SQL: AND kategori_id = ?
            $query->where('kategori_id', $kategori_id);
        }


        // ========== FILTER TAHUN ==========

        // Ambil input tahun dari dropdown (input name="tahun")
        $tahun = $request->input('tahun');

        // Jika tahun dipilih
        if ($tahun) {
            // Tambahkan kondisi WHERE tahun_terbit = value
            $query->where('tahun_terbit', $tahun);

            // Query SQL: WHERE tahun_terbit = 2024
        }


        // ========== FILTER KETERSEDIAAN ==========

        // Ambil input ketersediaan dari dropdown (input name="ketersediaan")
        // Nilai yang mungkin: 'tersedia', 'habis', atau kosong (semua)
        $ketersediaan = $request->input('ketersediaan');

        // Filter berdasarkan stok
        // Menggunakan === untuk perbandingan ketat (strict comparison)
        if ($ketersediaan === 'tersedia') {
            // Buku dengan stok > 0 (masih tersedia untuk dipinjam/dibeli)
            $query->where('stok', '>', 0);

            // Query SQL: AND stok > 0
        } elseif ($ketersediaan === 'habis') {
            // Buku dengan stok = 0 (sudah habis)
            $query->where('stok', 0);

            // Query SQL: AND stok = 0
        }
        // Jika 'semua' atau tidak diisi, tidak ada filter stok


        // ========== FILTER RANGE HARGA ==========
        // Filter harga minimum dan maksimum untuk pencarian berdasarkan rentang harga
        // Ambil input harga minimum dari form (input name="min_harga")
        $min_harga = $request->input('min_harga');
        // Ambil input harga maksimum dari form (input name="max_harga")
        $max_harga = $request->input('max_harga');
        // Jika harga minimum diisi, tambahkan kondisi WHERE harga >= min_harga
        // SQL: AND harga >= ?
        if ($min_harga) {
            $query->where('harga', '>=', $min_harga);
        }
        // Jika harga maksimum diisi, tambahkan kondisi WHERE harga <= max_harga
        // SQL: AND harga <= ?
        // Kombinasi min dan max menghasilkan: AND harga BETWEEN min AND max
        if ($max_harga) {
            $query->where('harga', '<=', $max_harga);
        }

        // Save preferences to session
        // Simpan preferensi filter ke session jika ada minimal satu filter yang diisi
        // anyFilled() mengecek apakah salah satu dari field tersebut memiliki nilai non-kosong
        // session(['key' => value]) menyimpan data ke session yang persisten antar request
        if ($request->anyFilled(['keyword', 'kategori_id', 'tahun', 'ketersediaan', 'min_harga', 'max_harga'])) {
            session(['filter_buku' => $request->all()]);
        } elseif ($request->has('clear_filter')) {
            // Jika user menekan tombol clear filter, hapus session filter
            session()->forget('filter_buku');
            // Redirect ke halaman index tanpa filter
            return redirect()->route('buku.index');
        }

        // ========== EKSEKUSI QUERY ==========

        // latest() = orderBy('created_at', 'desc') — urutkan dari yang terbaru
        // get() = eksekusi query SQL yang sudah dibangun dan ambil hasilnya sebagai Collection
        // Pada titik ini, semua klausa WHERE yang ditambahkan di atas digabungkan menjadi satu query
        $bukus = $query->latest()->get();


        // ========== STATISTIK ==========

        // Hitung statistik dari hasil filter (bukan dari seluruh database)
        // Angka statistik akan mencerminkan hasil pencarian saat ini
        $totalBuku = $bukus->count();
        // where() di Collection = filter di memory (bukan query database baru)
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();


        // ========== DATA UNTUK DROPDOWN ==========

        // Ambil semua kategori dari database (untuk isi dropdown kategori)
        // Selalu ambil semua kategori, tidak dipengaruhi oleh filter yang aktif
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        // Ambil semua tahun unik dari database (untuk isi dropdown tahun)
        // distinct() = SELECT DISTINCT, pluck() = ambil satu kolom saja
        $tahuns = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');


        // ========== KIRIM DATA KE VIEW ==========

        // Kirim semua data ke view 'buku.index'
        // Variabel keyword, kategori_id, tahun, ketersediaan, min_harga, max_harga
        // dikirim agar form search di view bisa menampilkan kembali (retain)
        // nilai yang sudah diinput user sebelumnya (old input)
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategoris',
            'tahuns',
            'keyword',      // Untuk mengisi kembali form
            'kategori_id',  // Untuk mengisi kembali form
            'tahun',        // Untuk mengisi kembali form
            'ketersediaan', // Untuk mengisi kembali form
            'min_harga',
            'max_harga'
        ));
    }
}
