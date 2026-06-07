<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BukuExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_export_buku_ke_csv_berhasil()
    {
        // 1. Siapkan data buku palsu di database
        Buku::create([
            'kode_buku' => 'BK-PROG-001',
            'judul' => 'Belajar Laravel 11',
            'kategori' => 'Programming',
            'pengarang' => 'Penulis A',
            'penerbit' => 'Penerbit B',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'stok' => 10,
            'bahasa' => 'Inggris',
        ]);

        Buku::create([
            'kode_buku' => 'BK-DB-002',
            'judul' => 'Belajar Database PostgreSQL',
            'kategori' => 'Database',
            'pengarang' => 'Penulis C',
            'penerbit' => 'Penerbit D',
            'tahun_terbit' => 2023,
            'harga' => 60000,
            'stok' => 5,
            'bahasa' => 'Indonesia',
        ]);

        // 2. Jalankan request export ke CSV
        $response = $this->get(route('buku.export'));

        // 3. Verifikasi response status dan headers
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringStartsWith('attachment; filename="buku_', $contentDisposition);
        $this->assertStringEndsWith('.csv"', $contentDisposition);

        // 4. Dapatkan konten output CSV
        $content = $response->streamedContent();

        // 5. Verifikasi headers & data pada CSV
        $this->assertStringContainsString('"Kode Buku",Judul,Kategori,Pengarang,Penerbit,Tahun,ISBN,Harga,Stok', $content);
        $this->assertStringContainsString('BK-PROG-001,"Belajar Laravel 11",Programming,"Penulis A","Penerbit B",2024,,50000.00,10', $content);
        $this->assertStringContainsString('BK-DB-002,"Belajar Database PostgreSQL",Database,"Penulis C","Penerbit D",2023,,60000.00,5', $content);
    }
}
