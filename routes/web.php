<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Buku - Custom & CRUD
    Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
    Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulk-delete');
    Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
    Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])->name('buku.kategori');
    Route::resource('buku', BukuController::class);

    // Anggota - Custom & CRUD
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('/anggota/search', [AnggotaController::class, 'search'])->name('anggota.search');
    Route::resource('anggota', AnggotaController::class);

    // Transaksi - CRUD + Custom routes
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::resource('transaksi', TransaksiController::class);
    Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});

require __DIR__ . '/auth.php';
