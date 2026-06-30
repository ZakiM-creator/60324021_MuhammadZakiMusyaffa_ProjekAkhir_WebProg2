<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-add missing columns to 'transaksis' table safely
        try {
            if (Schema::hasTable('transaksis')) {
                Schema::table('transaksis', function (Blueprint $table) {
                    if (!Schema::hasColumn('transaksis', 'tanggal_dikembalikan')) {
                        $table->date('tanggal_dikembalikan')->nullable()->after('tanggal_kembali');
                    }
                    if (!Schema::hasColumn('transaksis', 'keterangan')) {
                        $table->text('keterangan')->nullable()->after('denda');
                    }
                });
            }
            
            // Fix outdated ENUM values for 'status' column in 'transaksis' table
            DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('Pinjam', 'Kembali', 'Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam'");
        } catch (\Exception $e) {
            // Ignore if DB is not ready yet
        }
    }
}
