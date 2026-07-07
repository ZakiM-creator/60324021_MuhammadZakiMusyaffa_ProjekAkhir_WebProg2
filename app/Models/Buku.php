<?php

namespace App\Models;

// HasFactory digunakan agar model dapat membuat data tiruan (dummy data) menggunakan Factory/Seeder.
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Buku extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit karena nama tabelnya singular ('buku' bukan 'bukus').
    protected $table = 'buku';

    // Kolom-kolom yang diizinkan untuk mass assignment saat insert/update.
    protected $fillable = [
        'kode_buku',
        'judul',
        'kategori_id',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'harga',
        'stok',
        'deskripsi',
        'bahasa',
    ];

    // $casts mengubah tipe data nilai dari database (yang biasanya string) 
    // menjadi tipe data PHP asli (integer, float, dsb) secara otomatis saat diakses.
    protected $casts = [
        'tahun_terbit' => 'integer',
        'harga' => 'decimal:2', // Menyimpan harga dalam 2 angka desimal
        'stok' => 'integer',
        'kategori_id' => 'integer',
    ];

    // --- ACCESSORS ---
    // Accessor mengubah cara suatu atribut dipanggil, ditandai dengan get[Nama]Attribute.
    
    // Dipanggil dengan `$buku->harga_format`. Mengubah 75000 menjadi "Rp 75.000".
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Dipanggil dengan `$buku->tersedia`. Mengembalikan boolean True jika stok > 0.
    public function getTersediaAttribute(): bool
    {
        return $this->stok > 0;
    }

    // Dipanggil dengan `$buku->status_stok_badge`. Menghasilkan HTML badge warna-warni 
    // berdasarkan jumlah stok yang tersisa.
    public function getStatusStokBadgeAttribute(): string
    {
        if ($this->stok == 0) {
            return '<span class="badge bg-danger">Habis</span>';
        }

        if ($this->stok >= 1 && $this->stok <= 5) {
            return '<span class="badge bg-warning">Menipis</span>';
        }

        if ($this->stok >= 6 && $this->stok <= 15) {
            return '<span class="badge bg-info">Sedang</span>';
        }

        return '<span class=badge bg-success>Aman</span>';
    }

    // Menghasilkan string "Buku Baru" jika tahun >= 2024, selain itu "Buku Lama".
    public function getTahunLabelAttribute(): string
    {
        return $this->tahun_terbit >= 2024 ? 'Buku Baru' : 'Buku Lama';
    }

    // --- SCOPES ---
    // Scope mempermudah penulisan query builder. 
    
    // Bisa dipanggil dengan: Buku::tersedia()->get()
    // Ekivalen dengan: Buku::where('stok', '>', 0)->get()
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Filter berdasarkan kategori ID
    public function scopeKategori($query, $kategori_id)
    {
        return $query->where('kategori_id', $kategori_id);
    }

    // Filter stok menipis (<5)
    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<', 5);
    }

    // Filter range harga
    public function scopeHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }

    // Filter terbitan tahun >= 2024
    public function scopeTerbaru($query)
    {
        return $query->where('tahun_terbit', '>=', 2024);
    }

    // --- RELATIONS ---
    
    // Relasi BelongsTo ke Kategori. Satu buku memiliki satu kategori.
    public function kategoriRel()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi HasMany ke Transaksi. Satu buku bisa memiliki banyak record transaksi peminjaman.
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
