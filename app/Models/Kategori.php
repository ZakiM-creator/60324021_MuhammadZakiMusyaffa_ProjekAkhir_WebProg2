<?php

namespace App\Models;

// Mengimpor class Model bawaan Laravel sebagai induk dari class Kategori
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Secara default, Laravel mencari tabel 'kategoris' (plural).
    // Karena tabel di database bernama 'kategori' (singular), kita harus mendefinisikannya secara eksplisit.
    protected $table = 'kategori';

    // $fillable mendefinisikan kolom-kolom apa saja yang boleh diisi secara langsung
    // melalui metode mass assignment (seperti Kategori::create([...])).
    // Ini adalah fitur keamanan untuk mencegah user memasukkan data ke kolom yang tidak semestinya.
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'warna',
    ];

    // Mendifinisikan relasi One-to-Many (satu kategori memiliki banyak buku).
    // Method ini mengembalikan relasi hasMany ke model Buku.
    public function bukus()
    {
        // Parameter kedua ('kategori_id') adalah foreign key di tabel buku.
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
