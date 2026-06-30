<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'anggota';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
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

    /**
     * Tipe casting untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];

    /**
     * Accessor untuk menghitung umur.
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Accessor untuk lama menjadi anggota (dalam hari).
     */
    public function getLamaAnggotaAttribute(): int
    {
        return  (int) floor(
        Carbon::parse($this->tanggal_daftar)->diffInDays(now())
        );
    }


    public function getStatusBadgeAttribute(): string
    {
        return $this->status === 'Aktif'
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>';
    }

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


    /**
     * Scope untuk filter anggota aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope untuk filter berdasarkan jenis kelamin.
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }


    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereMonth('tanggal_daftar', now()->month)
            ->whereYear('tanggal_daftar', now()->year);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
