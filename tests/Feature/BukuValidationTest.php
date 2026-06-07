<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BukuValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_format_kode_buku_tidak_valid()
    {
        $response = $this->post(route('buku.store'), [
            'kode_buku' => 'SALAH-FORMAT-123',
            'judul' => 'Belajar Laravel',
            'kategori' => 'Programming',
            'pengarang' => 'Penulis A',
            'penerbit' => 'Penerbit B',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'stok' => 10,
            'bahasa' => 'Inggris',
        ]);

        $response->assertSessionHasErrors(['kode_buku']);
    }

    /** @test */
    public function test_format_kode_buku_valid()
    {
        $response = $this->post(route('buku.store'), [
            'kode_buku' => 'BK-PROG-001',
            'judul' => 'Belajar Laravel',
            'kategori' => 'Programming',
            'pengarang' => 'Penulis A',
            'penerbit' => 'Penerbit B',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'stok' => 10,
            'bahasa' => 'Inggris',
        ]);

        $response->assertSessionDoesntHaveErrors(['kode_buku']);
    }

    /** @test */
    public function test_kategori_programming_harus_bahasa_inggris()
    {
        $response = $this->post(route('buku.store'), [
            'kode_buku' => 'BK-PROG-001',
            'judul' => 'Belajar Laravel',
            'kategori' => 'Programming',
            'pengarang' => 'Penulis A',
            'penerbit' => 'Penerbit B',
            'tahun_terbit' => 2024,
            'harga' => 50000,
            'stok' => 10,
            'bahasa' => 'Indonesia', // Seharusnya gagal karena kategori Programming
        ]);

        $response->assertSessionHasErrors(['bahasa']);
        $this->assertEquals(
            'Jika kategori adalah Programming, bahasa harus Inggris.',
            session('errors')->first('bahasa')
        );
    }

    /** @test */
    public function test_tahun_terbit_di_bawah_2000_stok_maksimal_5()
    {
        $response = $this->post(route('buku.store'), [
            'kode_buku' => 'BK-DB-001',
            'judul' => 'Buku Database Lawas',
            'kategori' => 'Database',
            'pengarang' => 'Penulis A',
            'penerbit' => 'Penerbit B',
            'tahun_terbit' => 1998, // Tahun < 2000
            'harga' => 50000,
            'stok' => 6, // Seharusnya gagal karena > 5
            'bahasa' => 'Indonesia',
        ]);

        $response->assertSessionHasErrors(['stok']);
        $this->assertEquals(
            'Jika tahun terbit di bawah 2000, stok maksimal adalah 5.',
            session('errors')->first('stok')
        );
    }
}
