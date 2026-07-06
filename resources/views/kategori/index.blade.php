<x-app-layout theme="bootstrap" title="Manajemen Kategori">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-tags text-primary"></i> Manajemen Kategori
            </h2>
            <p class="text-muted">Kelola data kategori buku di perpustakaan Anda.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex align-items-center justify-content-md-end">
            <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
    </div>



    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
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
                    <tbody>
                        @forelse ($kategoris as $kategori)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">
                                {{ $kategori->nama_kategori }}
                            </td>
                            <td>
                                {{ Str::limit($kategori->deskripsi, 50) ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $kategori->warna }}">
                                    {{ $kategori->warna }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $kategori->bukus_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning" title="Edit Kategori">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus Kategori">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
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
