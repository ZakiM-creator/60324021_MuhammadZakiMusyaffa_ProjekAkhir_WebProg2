<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Buku - CRUD
    Route::resource('buku', BukuController::class);

    // Anggota - CRUD
    Route::resource('anggota', AnggotaController::class);

    // Transaksi - CRUD + Custom routes
    Route::resource('transaksi', TransaksiController::class);
    Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});

require __DIR__ . '/auth.php';
