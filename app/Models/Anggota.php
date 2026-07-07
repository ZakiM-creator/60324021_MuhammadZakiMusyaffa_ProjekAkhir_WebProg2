<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon; // Library pembantu manipulasi waktu

class Anggota extends Model
{
    use HasFactory;

    // Menyesuaikan dengan nama tabel di database ('anggota' tanpa 's')
    protected $table = 'anggota';

    // Kolom-kolom yang aman untuk dilakukan mass assignment
    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'tanggal_daftar',
        'status',
    ];

    // Mengubah string tanggal menjadi objek Carbon secara otomatis
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];

    // --- ACCESSORS ---
    
    // Dipanggil dengan `$anggota->umur`. 
    // Otomatis menghitung usia (tahun) dari tanggal_lahir hingga hari ini menggunakan property 'age' dari objek Carbon.
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    // Menghitung berapa hari anggota sudah terdaftar (selisih hari dari daftar sampai sekarang).
    public function getLamaAnggotaAttribute(): int
    {
        return (int) floor(
            Carbon::parse($this->tanggal_daftar)->diffInDays(now())
        );
    }

    // Mengembalikan elemen span HTML berupa label Status Aktif (hijau) atau Nonaktif (abu).
    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'Aktif'
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>';
    }

    // Mengklasifikasikan usia anggota menjadi 'Remaja', 'Dewasa', atau 'Senior' berdasarkan Accessor 'umur'.
    public function getKategoriUsiaAttribute(): string
    {
        if ($this->umur < 20) {
            return 'Remaja';
        }

        if ($this->umur >= 20 && $this->umur <= 50) {
            return 'Dewasa';
        }

        return 'Senior';
    }


    // --- SCOPES ---
    
    // Filter pencarian khusus untuk anggota Aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    // Filter anggota berdasarkan Laki-laki/Perempuan
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    // Mencari anggota yang tanggal daftarnya berada pada bulan dan tahun yang sama dengan saat ini.
    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereMonth('tanggal_daftar', now()->month)
            ->whereYear('tanggal_daftar', now()->year);
    }

    // --- RELATIONS ---
    
    // Relasi HasMany ke Transaksi. Satu anggota bisa melakukan banyak transaksi peminjaman.
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
