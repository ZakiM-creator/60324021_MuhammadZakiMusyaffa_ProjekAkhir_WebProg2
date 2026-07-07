{{-- 
============================================================
FILE: kategori/index.blade.php
FUNGSI: Menampilkan daftar seluruh kategori buku dalam bentuk tabel
============================================================
--}}
{{-- Memanggil komponen layout utama (app.blade.php) dengan tema bootstrap --}}
<x-app-layout theme="bootstrap" title="Manajemen Kategori">
    {{-- Baris untuk Header Halaman --}}
    <div class="row mb-4">
        {{-- Kolom kiri untuk Judul dan Deskripsi --}}
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-tags text-primary"></i> Manajemen Kategori
            </h2>
            <p class="text-muted">Kelola data kategori buku di perpustakaan Anda.</p>
        </div>
        {{-- Kolom kanan untuk Tombol Tambah --}}
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex align-items-center justify-content-md-end">
            {{-- Link menuju form halaman tambah kategori --}}
            <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
    </div>

    {{-- Kotak utama berisi Tabel Data --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            {{-- div table-responsive agar tabel bisa di-scroll horizontal di layar kecil (HP) --}}
            <div class="table-responsive">
                {{-- Komponen tabel Bootstrap dengan efek hover dan baris belang-belang (striped) --}}
                <table class="table table-hover table-striped align-middle mb-0">
                    {{-- Bagian Kepala Tabel (Warna Gelap) --}}
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Nama Kategori</th>
                            <th width="35%">Deskripsi</th>
                            <th width="15%" class="text-center">Warna Label</th>
                            <th width="10%" class="text-center">Jml Buku</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Bagian Isi Tabel --}}
                    <tbody>
                        {{-- @forelse: Sama seperti foreach, tapi memiliki blok @empty jika data kosong --}}
                        @forelse ($kategoris as $kategori)
                        <tr>
                            {{-- Nomor urut otomatis dari fungsi $loop bawaan Blade --}}
                            <td class="text-center">{{ $loop->iteration }}</td>
                            
                            {{-- Nama Kategori (Tebal) --}}
                            <td class="fw-bold">
                                {{ $kategori->nama_kategori }}
                            </td>
                            
                            {{-- Deskripsi dibatasi maksimal 50 karakter pakai helper Str::limit. Jika null tampil '-' --}}
                            <td>
                                {{ Str::limit($kategori->deskripsi, 50) ?? '-' }}
                            </td>
                            
                            {{-- Menampilkan warna yang dipilih ke dalam class bg-* Bootstrap --}}
                            <td class="text-center">
                                <span class="badge bg-{{ $kategori->warna }}">
                                    {{ $kategori->warna }}
                                </span>
                            </td>
                            
                            {{-- Jumlah buku dalam kategori ini (didapat dari eager loading withCount di Controller) --}}
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $kategori->bukus_count }}
                                </span>
                            </td>
                            
                            {{-- Tombol Aksi (Edit & Hapus) --}}
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    {{-- Tombol menuju route edit --}}
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning" title="Edit Kategori">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    {{-- Form khusus untuk menghapus data. Method harus POST lalu dioverride dengan @method('DELETE') --}}
                                    {{-- class btn-delete-confirm memicu alert konfirmasi global di app.blade.php --}}
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-delete-confirm" data-judul="{{ $kategori->nama_kategori }}" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        {{-- Bagian ini berjalan JIKA array/koleksi $kategoris bernilai kosong --}}
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                Belum ada data kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
