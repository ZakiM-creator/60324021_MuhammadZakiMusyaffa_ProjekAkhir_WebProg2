<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Buku extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'buku';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_buku',
        'judul',
        'kategori',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'harga',
        'stok',
        'deskripsi',
        'bahasa',
    ];

    /**
     * Tipe casting untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_terbit' => 'integer',
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    /**
     * Accessor untuk format harga.
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor untuk status ketersediaan.
     */
    public function getTersediaAttribute(): bool
    {
        return $this->stok > 0;
    }

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

    public function getTahunLabelAttribute(): string
    {
        return $this->tahun_terbit >= 2024 ? 'Buku Baru' : 'Buku Lama';
    }


    /**
     * Scope untuk filter buku tersedia.
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope untuk filter berdasarkan kategori.
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<', 5);
    }

    public function scopeHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }

    public function scopeTerbaru($query)
    {
        return $query->where('tahun_terbit', '>=', 2024);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
