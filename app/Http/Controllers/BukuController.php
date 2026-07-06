<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Kategori;
use App\Exports\BukuExport;
use Maatwebsite\Excel\Facades\Excel;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika ada clear_filter, hapus session dan load semua
        if ($request->has('clear_filter')) {
            session()->forget('filter_buku');
            return redirect()->route('buku.index');
        }

        // Restore filter preferences from session if exist
        if (session()->has('filter_buku') && empty($request->all())) {
            return redirect()->route('buku.search', session('filter_buku'));
        }

        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();

        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();

        // Data untuk dropdown
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $tahuns = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        return view('buku.index', compact(
            'bukus', 'totalBuku', 'bukuTersedia', 'bukuHabis', 'kategoris', 'tahuns'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            Buku::create($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // ........
        $buku = Buku::findOrFail($id);

        //........
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);

            // Update buku dengan validated data
            $buku->update($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $judulBuku = $buku->judul;

            // Delete buku
            $buku->delete();

            // Redirect dengan success message
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
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('buku_ids');

            if (empty($ids)) {
                return redirect()->route('buku.index')
                    ->with('error', 'Silakan pilih buku yang ingin dihapus terlebih dahulu.');
            }

            Buku::whereIn('id', $ids)->delete();

            return redirect()->route('buku.index')
                ->with('success', count($ids) . ' buku berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku secara massal: ' . $e->getMessage());
        }
    }

    /**
     * Export data buku ke Excel.
     */
    public function export()
    {
        return Excel::download(new BukuExport, 'buku_' . date('Y-m-d_His') . '.xlsx');
    }

    public function filterKategori($kategori_id)
    {
        $bukus = Buku::where('kategori_id', $kategori_id)->latest()->get();

        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();

        // Data untuk dropdown
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $tahuns = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

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
     * @param Request $request - Object request berisi input dari form
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // ========== INISIALISASI QUERY BUILDER ==========

        // Membuat query builder instance
        // query() mengembalikan Eloquent Builder, bukan hasil query
        $query = Buku::query();


        // ========== FILTER KEYWORD (SEARCH) ==========

        // Ambil input keyword dari form
        $keyword = $request->input('keyword');

        // Jika keyword diisi, cari di 3 kolom: judul, pengarang, penerbit
        if ($keyword) {
            // where() dengan closure untuk grouping kondisi OR
            $query->where(function ($q) use ($keyword) {
                // LIKE '%keyword%' = mencari substring di kolom
                $q->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('pengarang', 'like', "%{$keyword}%")
                    ->orWhere('penerbit', 'like', "%{$keyword}%");
            });

            // Query SQL yang dihasilkan:
            // WHERE (judul LIKE '%keyword%' OR pengarang LIKE '%keyword%' OR penerbit LIKE '%keyword%')
        }


        // ========== FILTER KATEGORI ==========

        // Ambil input kategori dari dropdown
        $kategori_id = $request->input('kategori_id');

        // Jika kategori dipilih (bukan "Semua")
        if ($kategori_id) {
            // Tambahkan kondisi WHERE kategori_id = value
            $query->where('kategori_id', $kategori_id);
        }


        // ========== FILTER TAHUN ==========

        // Ambil input tahun dari dropdown
        $tahun = $request->input('tahun');

        // Jika tahun dipilih
        if ($tahun) {
            // Tambahkan kondisi WHERE tahun_terbit = value
            $query->where('tahun_terbit', $tahun);

            // Query SQL: WHERE tahun_terbit = 2024
        }


        // ========== FILTER KETERSEDIAAN ==========

        // Ambil input ketersediaan dari dropdown
        $ketersediaan = $request->input('ketersediaan');

        // Filter berdasarkan stok
        if ($ketersediaan === 'tersedia') {
            // Buku dengan stok > 0
            $query->where('stok', '>', 0);

            // Query SQL: WHERE stok > 0
        } elseif ($ketersediaan === 'habis') {
            // Buku dengan stok = 0
            $query->where('stok', 0);

            // Query SQL: WHERE stok = 0
        }
        // Jika 'semua' atau tidak diisi, tidak ada filter stok


        // ========== FILTER RANGE HARGA ==========
        $min_harga = $request->input('min_harga');
        $max_harga = $request->input('max_harga');
        if ($min_harga) {
            $query->where('harga', '>=', $min_harga);
        }
        if ($max_harga) {
            $query->where('harga', '<=', $max_harga);
        }

        // Save preferences to session
        if ($request->anyFilled(['keyword', 'kategori_id', 'tahun', 'ketersediaan', 'min_harga', 'max_harga'])) {
            session(['filter_buku' => $request->all()]);
        } elseif ($request->has('clear_filter')) {
            session()->forget('filter_buku');
            return redirect()->route('buku.index');
        }

        // ========== EKSEKUSI QUERY ==========

        // latest() = orderBy('created_at', 'desc')
        // get() = eksekusi query dan ambil hasil
        $bukus = $query->latest()->get();


        // ========== STATISTIK ==========

        // Hitung statistik dari hasil filter
        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();


        // ========== DATA UNTUK DROPDOWN ==========

        // Ambil semua kategori dari database
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        // Ambil semua tahun unik dari database
        $tahuns = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');


        // ========== KIRIM DATA KE VIEW ==========

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
