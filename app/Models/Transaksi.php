<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    // Kolom-kolom yang akan diisi saat pembuatan / update transaksi
    protected $fillable = [
        'kode_transaksi',
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'denda',
        'keterangan',
    ];

    // Konversi string ke objek Carbon instance untuk mempermudah perhitungan jarak tanggal
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    // --- RELATIONS ---
    
    // Relasi BelongsTo ke Anggota. Sebuah transaksi dimiliki oleh satu anggota.
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // Relasi BelongsTo ke Buku. Sebuah transaksi mengacu pada satu buku.
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // --- ACCESSORS ---
    
    // Menghitung berapa lama (hari) buku tersebut dipinjam.
    public function getDurasiPeminjamanAttribute()
    {
        // Jika sudah dikembalikan, hitung selisih dari tanggal pinjam ke dikembalikan.
        if ($this->tanggal_dikembalikan) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_dikembalikan);
        }
        // Jika belum (masih dipinjam), hitung selisih dari tanggal pinjam ke HARI INI (now).
        return $this->tanggal_pinjam->diffInDays(now());
    }

    // Menghitung hari keterlambatan.
    public function getTerlambatAttribute()
    {
        // Jika statusnya sudah dikembalikan, cek apakah tgl dikembalikan melampaui tgl kembali (tenggat waktu).
        if ($this->status == 'Dikembalikan') {
            if ($this->tanggal_dikembalikan > $this->tanggal_kembali) {
                return $this->tanggal_kembali->diffInDays($this->tanggal_dikembalikan);
            }
            return 0; // Tidak terlambat
        }

        // Jika belum dikembalikan, cek apakah hari ini (now) sudah melewati tenggat waktu.
        if (now() > $this->tanggal_kembali) {
            return $this->tanggal_kembali->diffInDays(now());
        }

        return 0;
    }

    // HTML span penanda status "Dipinjam" (kuning) atau "Dikembalikan" (hijau).
    public function getStatusBadgeAttribute()
    {
        return $this->status == 'Dipinjam'
            ? '<span class="badge bg-warning text-dark">Dipinjam</span>'
            : '<span class="badge bg-success">Dikembalikan</span>';
    }
}
