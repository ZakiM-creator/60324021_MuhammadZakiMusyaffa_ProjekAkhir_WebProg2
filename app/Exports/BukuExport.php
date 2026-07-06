<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
 
class BukuExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Buku::with('kategoriRel')->get();
    }
 
    public function headings(): array
    {
        return [
            'Kode Buku',
            'Judul',
            'Kategori',
            'Pengarang',
            'Penerbit',
            'Tahun',
            'ISBN',
            'Harga',
            'Stok',
            'Tersedia',
        ];
    }

    /**
    * @param mixed $buku
    *
    * @return array
    */
    public function map($buku): array
    {
        return [
            $buku->kode_buku,
            $buku->judul,
            $buku->kategoriRel ? $buku->kategoriRel->nama_kategori : '-',
            $buku->pengarang,
            $buku->penerbit,
            $buku->tahun_terbit,
            $buku->isbn,
            $buku->harga,
            $buku->stok,
            $buku->tersedia ? 'Ya' : 'Tidak',
        ];
    }
}
