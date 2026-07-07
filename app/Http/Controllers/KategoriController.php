<?php

// ============================================================
// FILE: KategoriController.php
// FUNGSI: Mengelola CRUD data Kategori Buku
// ROUTE: Resource route → /kategori (index, create, store, edit, update, destroy)
// ============================================================

namespace App\Http\Controllers;

use App\Models\Kategori;                   // Model Eloquent untuk tabel `kategori`
use Illuminate\Http\Request;               // Class untuk menangani HTTP request

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori buku.
     * Route: GET /kategori
     */
    public function index()
    {
        // withCount('bukus') menambahkan kolom virtual 'bukus_count' ke setiap kategori.
        // Ini menjalankan subquery: SELECT COUNT(*) FROM buku WHERE kategori_id = kategori.id
        // latest() = ORDER BY created_at DESC (kategori terbaru di atas).
        // get() = eksekusi query, hasilnya adalah Collection of Kategori.
        $kategoris = Kategori::withCount('bukus')->latest()->get();

        // compact('kategoris') mengirim variabel $kategoris ke view.
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Menampilkan form tambah kategori baru.
     * Route: GET /kategori/create
     */
    public function create()
    {
        // Hanya menampilkan form kosong, tanpa perlu data dari database.
        return view('kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     * Route: POST /kategori
     *
     * @param Request $request — berisi data form yang dikirim user
     */
    public function store(Request $request)
    {
        // LANGKAH 1: Validasi input dari form.
        //   'required'     → field wajib diisi
        //   'string'       → harus bertipe string
        //   'max:50'       → maksimal 50 karakter
        //   'unique:kategori,nama_kategori' → nama_kategori harus unik di tabel kategori
        //   'nullable'     → boleh kosong/null
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'required|string|max:20',
        ]);

        // LANGKAH 2: Buat record baru di tabel `kategori` dengan data yang sudah divalidasi.
        // Kategori::create() menggunakan mass assignment ($fillable di Model).
        Kategori::create($validated);

        // LANGKAH 3: Redirect ke halaman index dengan flash message sukses.
        // with('success', '...') menyimpan pesan ke session, dibaca oleh view
        // untuk menampilkan notifikasi SweetAlert.
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kategori.
     * Route: GET /kategori/{kategori}/edit
     *
     * Laravel otomatis melakukan Route Model Binding:
     * {kategori} di URL (misal: /kategori/3/edit) → Laravel cari Kategori::find(3)
     * dan inject hasilnya ke parameter $kategori.
     *
     * @param Kategori $kategori — object model yang sudah di-resolve oleh Laravel
     */
    public function edit(Kategori $kategori)
    {
        // Kirim data kategori ke view untuk ditampilkan di form edit.
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori di database.
     * Route: PUT/PATCH /kategori/{kategori}
     *
     * @param Request $request — data form yang dikirim
     * @param Kategori $kategori — model yang akan diupdate (Route Model Binding)
     */
    public function update(Request $request, Kategori $kategori)
    {
        // Validasi dengan pengecualian unique untuk record sendiri.
        // 'unique:kategori,nama_kategori,' . $kategori->id
        // → cek unik tapi ABAIKAN record dengan id ini sendiri.
        // Tanpa pengecualian ini, edit tanpa mengganti nama akan gagal karena "sudah ada".
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'required|string|max:20',
        ]);

        // update() menjalankan query: UPDATE kategori SET ... WHERE id = $kategori->id
        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     * Route: DELETE /kategori/{kategori}
     *
     * @param Kategori $kategori — model yang akan dihapus (Route Model Binding)
     */
    public function destroy(Kategori $kategori)
    {
        // LANGKAH 1: Cek apakah kategori masih memiliki buku.
        // bukus() adalah relasi hasMany di Model Kategori.
        // count() > 0 berarti masih ada buku yang terhubung.
        // Jika dihapus paksa, buku-buku tsb akan kehilangan kategori (data rusak/orphan).
        if ($kategori->bukus()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori karena masih memiliki buku.');
        }

        // LANGKAH 2: Jika aman (tidak ada buku terkait), hapus kategori.
        // delete() menjalankan: DELETE FROM kategori WHERE id = $kategori->id
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
