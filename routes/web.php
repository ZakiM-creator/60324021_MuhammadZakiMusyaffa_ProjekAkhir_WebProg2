`<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    // return view('welcome');
    return view('home');
})->name('home');

// Route::get('buku', function () {
//     return view('buku');
// })->name('buku.index');

// Custom route untuk filter kategori (harus SEBELUM resource route)
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])->name('buku.kategori');

// Route untuk dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route untuk search & filter buku (harus SEBELUM resource route)
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');

Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulk-delete');

// Route untuk export data buku ke CSV (harus SEBELUM resource route)
Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');

// Resource route untuk Buku
Route::resource('buku', BukuController::class);

// Resource route untuk Anggota
Route::resource('anggota', AnggotaController::class);




// Route::get('anggota', function () {
//     return view('anggota.index');
// })->name('anggota.index');

// Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
// Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');

// ========== TESTING BUKU ==========

// List all buku
// Route::get('/buku', function () {
//     $bukus = Buku::all();

//     $html = '<h1>Daftar Buku</h1>';
//     $html .= '<a href="/buku/create">Tambah Buku</a><br /><br />';
//     $html .= '<table border="1" cellpadding="10">';
//     $html .= '<tr>
//                 <th>ID</th>
//                 <th>Kode</th>
//                 <th>Judul</th>
//                 <th>Kategori</th>
//                 <th>Harga</th>
//                 <th>Stok</th>
//                 <th>Aksi</th>
//               </tr>';

//     foreach ($bukus as $buku) {
//         $html .= '<tr>';
//         $html .= '<td>' . $buku->id . '</td>';
//         $html .= '<td>' . $buku->kode_buku . '</td>';
//         $html .= '<td>' . $buku->judul . '</td>';
//         $html .= '<td>' . $buku->kategori . '</td>';
//         $html .= '<td>' . $buku->harga_format . '</td>';
//         $html .= '<td>' . $buku->stok . '</td>';
//         $html .= '<td>
//                     <a href="/buku/' . $buku->id . '">Detail</a> | 
//                     <a href="/buku/' . $buku->id . '/edit">Edit</a>
//                   </td>';
//         $html .= '</tr>';
//     }

//     $html .= '</table>';

//     return $html;
// });

// Route::post('/buku/store', function () {

//     Buku::create([
//         'kode_buku' => request('kode_buku'),
//         'judul' => request('judul'),
//         'kategori' => request('kategori'),
//         'pengarang' => request('pengarang'),
//         'penerbit' => request('penerbit'),
//         'tahun_terbit' => request('tahun_terbit'),
//         'isbn' => request('isbn'),
//         'harga' => request('harga'),
//         'stok' => request('stok'),
//         'deskripsi' => request('deskripsi'),
//         'bahasa' => request('bahasa'),
//     ]);

//     return redirect('/buku');
// });

// Route::get('/buku/create', function () {
//     return '
//         <h1>Tambah Buku</h1>

//         <form method="POST" action="/buku/store">
//             <input type="hidden" name="_token" value="' . csrf_token() . '">

//             <p>Kode Buku:</p>
//             <input type="text" name="kode_buku">

//             <p>Judul:</p>
//             <input type="text" name="judul">

//             <p>Kategori:</p>
//             <input type="text" name="kategori">

//             <p>Harga:</p>
//             <input type="number" name="harga">

//             <p>Stok:</p>
//             <input type="number" name="stok">

//             <p>Pengarang:</p>
//             <input type="text" name="pengarang">

//             <p>Penerbit:</p>
//             <input type="text" name="penerbit">

//             <p>Tahun Terbit:</p>
//             <input type="number" name="tahun_terbit">

//             <p>ISBN:</p>
//             <input type="text" name="isbn">

//             <p>Deskripsi:</p>
//             <input type="text" name="deskripsi">

//             <p>Bahasa:</p>
//             <input type="text" name="bahasa">

//             <br><br>

//             <button type="submit">Simpan</button>
//         </form>
//     ';
// });


// Show single buku
// Route::get('/buku/{id}', function ($id) {
//     $buku = Buku::findOrFail($id);

//     $html = '<h1>Detail Buku</h1>';
//     $html .= '<a href="/buku">Kembali</a><br /><br />';
//     $html .= '<table border="1" cellpadding="10">';
//     $html .= '<tr><th>Field</th><th>Value</th></tr>';
//     $html .= '<tr><td>ID</td><td>' . $buku->id . '</td></tr>';
//     $html .= '<tr><td>Kode Buku</td><td>' . $buku->kode_buku . '</td></tr>';
//     $html .= '<tr><td>Judul</td><td>' . $buku->judul . '</td></tr>';
//     $html .= '<tr><td>Kategori</td><td>' . $buku->kategori . '</td></tr>';
//     $html .= '<tr><td>Pengarang</td><td>' . $buku->pengarang . '</td></tr>';
//     $html .= '<tr><td>Penerbit</td><td>' . $buku->penerbit . '</td></tr>';
//     $html .= '<tr><td>Tahun</td><td>' . $buku->tahun_terbit . '</td></tr>';
//     $html .= '<tr><td>ISBN</td><td>' . $buku->isbn . '</td></tr>';
//     $html .= '<tr><td>Harga</td><td>' . $buku->harga_format . '</td></tr>';
//     $html .= '<tr><td>Stok</td><td>' . $buku->stok . '</td></tr>';
//     $html .= '<tr><td>Tersedia?</td><td>' . ($buku->tersedia ? 'Ya' : 'Tidak') . '</td></tr>';
//     $html .= '<tr><td>Created</td><td>' . $buku->created_at . '</td></tr>';
//     $html .= '<tr><td>Updated</td><td>' . $buku->updated_at . '</td></tr>';
//     $html .= '</table>';

//     return $html;
// });

// ========== TESTING ANGGOTA ==========

// List all anggota
// Route::get('/anggota', function () {
//     $anggotas = Anggota::all();

//     $html = '<h1>Daftar Anggota</h1>';
//     $html .= '<table border="1" cellpadding="10">';
//     $html .= '<tr>
//                 <th>ID</th>
//                 <th>Kode</th>
//                 <th>Nama</th>
//                 <th>Email</th>
//                 <th>Umur</th>
//                 <th>Status</th>
//                 <th>Aksi</th>
//               </tr>';

//     foreach ($anggotas as $anggota) {
//         $html .= '<tr>';
//         $html .= '<td>' . $anggota->id . '</td>';
//         $html .= '<td>' . $anggota->kode_anggota . '</td>';
//         $html .= '<td>' . $anggota->nama . '</td>';
//         $html .= '<td>' . $anggota->email . '</td>';
//         $html .= '<td>' . $anggota->umur . ' tahun</td>';
//         $html .= '<td>' . $anggota->status . '</td>';
//         $html .= '<td><a href="/anggota/' . $anggota->id . '">Detail</a></td>';
//         $html .= '</tr>';
//     }

//     $html .= '</table>';

//     return $html;
// });

// Show single anggota
// Route::get('/anggota/{id}', function ($id) {
//     $anggota = Anggota::findOrFail($id);

//     $html = '<h1>Detail Anggota</h1>';
//     $html .= '<a href="/anggota">Kembali</a><br /><br />';
//     $html .= '<table border="1" cellpadding="10">';
//     $html .= '<tr><th>Field</th><th>Value</th></tr>';
//     $html .= '<tr><td>Kode Anggota</td><td>' . $anggota->kode_anggota . '</td></tr>';
//     $html .= '<tr><td>Nama</td><td>' . $anggota->nama . '</td></tr>';
//     $html .= '<tr><td>Email</td><td>' . $anggota->email . '</td></tr>';
//     $html .= '<tr><td>Telepon</td><td>' . $anggota->telepon . '</td></tr>';
//     $html .= '<tr><td>Alamat</td><td>' . $anggota->alamat . '</td></tr>';
//     $html .= '<tr><td>Tanggal Lahir</td><td>' . $anggota->tanggal_lahir->format('d-m-Y') . '</td></tr>';
//     $html .= '<tr><td>Umur</td><td>' . $anggota->umur . ' tahun</td></tr>';
//     $html .= '<tr><td>Jenis Kelamin</td><td>' . $anggota->jenis_kelamin . '</td></tr>';
//     $html .= '<tr><td>Pekerjaan</td><td>' . $anggota->pekerjaan . '</td></tr>';
//     $html .= '<tr><td>Tanggal Daftar</td><td>' . $anggota->tanggal_daftar->format('d-m-Y') . '</td></tr>';
//     $html .= '<tr><td>Lama Anggota</td><td>' . $anggota->lama_anggota . ' hari</td></tr>';
//     $html .= '<tr><td>Status</td><td>' . $anggota->status . '</td></tr>';
//     $html .= '</table>';

//     return $html;
// });

// Testing Scope & Query
// Route::get('/test-query', function () {
//     $html = '<h1>Testing Query Eloquent</h1>';

//     // Buku tersedia
//     $tersedia = Buku::tersedia()->get();
//     $html .= '<h3>Buku Tersedia (Stok > 0): ' . $tersedia->count() . '</h3>';
//     $html .= '<ul>';
//     foreach ($tersedia as $buku) {
//         $html .= '<li>' . $buku->judul . ' (Stok: ' . $buku->stok . ')</li>';
//     }
//     $html .= '</ul>';

//     // Buku Programming
//     $programming = Buku::kategori('Programming')->get();
//     $html .= '<h3>Buku Programming: ' . $programming->count() . '</h3>';
//     $html .= '<ul>';
//     foreach ($programming as $buku) {
//         $html .= '<li>' . $buku->judul . '</li>';
//     }
//     $html .= '</ul>';

//     // Anggota Aktif
//     $aktif = Anggota::aktif()->get();
//     $html .= '<h3>Anggota Aktif: ' . $aktif->count() . '</h3>';
//     $html .= '<ul>';
//     foreach ($aktif as $anggota) {
//         $html .= '<li>' . $anggota->nama . ' (' . $anggota->email . ')</li>';
//     }
//     $html .= '</ul>';

//     return $html;
// });

// Route::get('/test-accessor-scope', function () {
//     $bukus       = \App\Models\Buku::all();
//     $terbaru     = \App\Models\Buku::terbaru()->get();
//     $stokMenipis = \App\Models\Buku::stokMenipis()->get();
//     $anggotas    = \App\Models\Anggota::all();
//     $bulanIni    = \App\Models\Anggota::terdaftarBulanIni()->get();

//     $html =
//         '<!DOCTYPE html>
// <html lang="id">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Testing Accessor &amp; Scope</title>
//     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
//     <style>
//         body { background-color: #f8f9fa; }
//         .section-card { margin-bottom: 1.5rem; }
//     </style>
// </head>
// <body>
// <div class="container py-4">

//     <h1 class="mb-1 fw-bold">Testing Accessor &amp; Scope</h1>
//     <p class="text-muted mb-4">Demonstrasi Eloquent Accessor &amp; Query Scope — Laravel</p>

//     <!-- ===== BUKU ===== -->
//     <h5 class="text-uppercase text-secondary fw-semibold mb-3" style="letter-spacing:.05em">Buku</h5>

//     <!-- Semua Buku -->
//     <div class="card section-card shadow-sm">
//         <div class="card-header bg-white fw-semibold">
//             Semua Buku
//             <span class="badge bg-primary ms-2">' . $bukus->count() . '</span>
//         </div>
//         <div class="card-body p-0">
//             <table class="table table-hover table-sm mb-0">
//                 <thead class="table-light">
//                     <tr>
//                         <th class="ps-3">Judul</th>
//                         <th>Stok</th>
//                         <th>Status Stok</th>
//                         <th>Tahun Label</th>
//                     </tr>
//                 </thead>
//                 <tbody>';

//     foreach ($bukus as $buku) {
//         $html .= '<tr>
//                         <td class="ps-3">' . e($buku->judul) . '</td>
//                         <td>' . $buku->stok . '</td>
//                         <td>' . $buku->status_stok_badge . '</td>
//                         <td><span class="badge ' . ($buku->tahun_label === 'Buku Baru' ? 'bg-success' : 'bg-secondary') . '">'
//             . e($buku->tahun_label) . '</span></td>
//                     </tr>';
//     }

//     $html .= '      </tbody>
//             </table>
//         </div>
//     </div>

//     <!-- Buku Terbaru -->
//     <div class="card section-card shadow-sm">
//         <div class="card-header bg-white fw-semibold">
//             Buku Terbaru <small class="text-muted fw-normal">(scope: terbaru — tahun &ge; 2024)</small>
//             <span class="badge bg-success ms-2">' . $terbaru->count() . '</span>
//         </div>
//         <ul class="list-group list-group-flush">';

//     if ($terbaru->isEmpty()) {
//         $html .= '<li class="list-group-item text-muted fst-italic">Tidak ada data.</li>';
//     }
//     foreach ($terbaru as $buku) {
//         $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">
//                         ' . e($buku->judul) . '
//                         <span class="badge bg-success">' . $buku->tahun_terbit . '</span>
//                     </li>';
//     }

//     $html .= '  </ul>
//     </div>

//     <!-- Buku Stok Menipis -->
//     <div class="card section-card shadow-sm">
//         <div class="card-header bg-white fw-semibold">
//             Buku Stok Menipis <small class="text-muted fw-normal">(scope: stokMenipis — stok &lt; 5)</small>
//             <span class="badge bg-warning text-dark ms-2">' . $stokMenipis->count() . '</span>
//         </div>
//         <ul class="list-group list-group-flush">';

//     if ($stokMenipis->isEmpty()) {
//         $html .= '<li class="list-group-item text-muted fst-italic">Tidak ada data.</li>';
//     }
//     foreach ($stokMenipis as $buku) {
//         $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">
//                         ' . e($buku->judul) . '
//                         <span class="badge bg-warning text-dark">Stok: ' . $buku->stok . '</span>
//                     </li>';
//     }

//     $html .= '  </ul>
//     </div>

//     <!-- ===== ANGGOTA ===== -->
//     <h5 class="text-uppercase text-secondary fw-semibold mb-3 mt-2" style="letter-spacing:.05em">Anggota</h5>

//     <!-- Semua Anggota -->
//     <div class="card section-card shadow-sm">
//         <div class="card-header bg-white fw-semibold">
//             Semua Anggota
//             <span class="badge bg-primary ms-2">' . $anggotas->count() . '</span>
//         </div>
//         <div class="card-body p-0">
//             <table class="table table-hover table-sm mb-0">
//                 <thead class="table-light">
//                     <tr>
//                         <th class="ps-3">Nama</th>
//                         <th>Status</th>
//                         <th>Kategori Usia</th>
//                     </tr>
//                 </thead>
//                 <tbody>';

//     foreach ($anggotas as $anggota) {
//         $html .= '<tr>
//                         <td class="ps-3">' . e($anggota->nama) . '</td>
//                         <td>' . $anggota->status_badge . '</td>
//                         <td>' . e($anggota->kategori_usia) . '</td>
//                     </tr>';
//     }

//     $html .= '      </tbody>
//             </table>
//         </div>
//     </div>

//     <!-- Anggota Terdaftar Bulan Ini -->
//     <div class="card section-card shadow-sm">
//         <div class="card-header bg-white fw-semibold">
//             Anggota Terdaftar Bulan Ini <small class="text-muted fw-normal">(scope: terdaftarBulanIni)</small>
//             <span class="badge bg-info text-dark ms-2">' . $bulanIni->count() . '</span>
//         </div>
//         <ul class="list-group list-group-flush">';

//     if ($bulanIni->isEmpty()) {
//         $html .= '<li class="list-group-item text-muted fst-italic">Tidak ada anggota yang mendaftar bulan ini.</li>';
//     }
//     foreach ($bulanIni as $anggota) {
//         $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">
//                         ' . e($anggota->nama) . '
//                         <span class="badge bg-info text-dark">' . $anggota->tanggal_daftar->format('d-m-Y') . '</span>
//                     </li>';
//     }

//     $html .= '  </ul>
//     </div>

// </div>
// <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
// </body>
// </html>';

//     return $html;
// });



// Route test koneksi database
// Route::get('/test-db', function () {
//     try {
//         DB::connection()->getPdo();
//         $dbName = DB::connection()->getDatabaseName();

//         return "Koneksi database berhasil!<br />Database: <strong>{$dbName}</strong>";
//     } catch (\Exception $e) {
//         return "Koneksi database gagal!<br />Error: " . $e->getMessage();
//     }
// });
