<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan data awal ke tabel kategori jika masih kosong
        $kategoriAda = DB::table('kategori')->count();
        if ($kategoriAda == 0) {
            DB::table('kategori')->insert([
                ['nama_kategori' => 'Programming', 'warna' => 'primary', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Database', 'warna' => 'success', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Web Design', 'warna' => 'info', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Networking', 'warna' => 'warning', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Data Science', 'warna' => 'danger', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Tambah kategori_id nullable (sementara nullable)
        Schema::table('buku', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('judul');
        });

        // 3. Mapping data dari enum kategori (string) ke kategori_id
        $kategoris = DB::table('kategori')->get();
        foreach ($kategoris as $kat) {
            DB::table('buku')->where('kategori', $kat->nama_kategori)->update(['kategori_id' => $kat->id]);
        }

        // 4. Hapus kolom kategori enum lama, dan jadikan kategori_id sebagai foreign key
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('kategori');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->enum('kategori', [
                'Programming',
                'Database',
                'Web Design',
                'Networking',
                'Data Science'
            ])->after('judul')->default('Programming');
        });

        // Mapping back data
        $kategoris = DB::table('kategori')->get();
        foreach ($kategoris as $kat) {
            DB::table('buku')->where('kategori_id', $kat->id)->update(['kategori' => $kat->nama_kategori]);
        }

        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('kategori_id');
        });
    }
};
