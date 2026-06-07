<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BukuBulkDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_bulk_delete_buku_berhasil()
    {
        // 1. Siapkan data buku palsu di database
        $buku1 = Buku::create([
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

        $buku2 = Buku::create([
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

        $buku3 = Buku::create([
            'kode_buku' => 'BK-NET-003',
            'judul' => 'Dasar Jaringan Komputer',
            'kategori' => 'Networking',
            'pengarang' => 'Penulis E',
            'penerbit' => 'Penerbit F',
            'tahun_terbit' => 2022,
            'harga' => 70000,
            'stok' => 2,
            'bahasa' => 'Indonesia',
        ]);

        // Verifikasi awal bahwa ketiga buku ada di database
        $this->assertDatabaseCount('buku', 3);

        // 2. Kirim request bulk delete untuk buku1 dan buku2
        $response = $this->post(route('buku.bulk-delete'), [
            'buku_ids' => [$buku1->id, $buku2->id]
        ]);

        // 3. Verifikasi response
        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('success', '2 buku berhasil dihapus!');

        // 4. Verifikasi bahwa database hanya menyisakan buku3
        $this->assertDatabaseCount('buku', 1);
        $this->assertDatabaseMissing('buku', ['id' => $buku1->id]);
        $this->assertDatabaseMissing('buku', ['id' => $buku2->id]);
        $this->assertDatabaseHas('buku', ['id' => $buku3->id]);
    }

    /** @test */
    public function test_bulk_delete_tanpa_memilih_buku_menampilkan_pesan_error()
    {
        // Kirim request bulk delete dengan array kosong
        $response = $this->post(route('buku.bulk-delete'), [
            'buku_ids' => []
        ]);

        // Verifikasi redirect dan session error
        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('error', 'Silakan pilih buku yang ingin dihapus terlebih dahulu.');
    }
}
