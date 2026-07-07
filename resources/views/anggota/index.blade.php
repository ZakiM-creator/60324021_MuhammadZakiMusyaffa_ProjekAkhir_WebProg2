{{-- Menggunakan komponen layout 'app' dengan tema bootstrap dan menetapkan judul "Daftar Anggota" --}}
<x-app-layout theme="bootstrap" title="Daftar Anggota">

{{-- Bagian Header Halaman: Menampilkan judul dan tombol aksi --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- Judul Halaman --}}
    <h1>
        <i class="bi bi-people"></i>
        Daftar Anggota
    </h1>
    {{-- Kelompok tombol aksi (Export dan Tambah) --}}
    <div class="d-flex gap-2">
        {{-- Tombol untuk mengekspor data anggota ke format Excel --}}
        <a href="{{ route('anggota.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        {{-- Tombol untuk menuju halaman tambah anggota --}}
        <a href="{{ route('anggota.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Anggota
        </a>
    </div>
</div>

{{-- Bagian Pencarian & Filter Lanjutan (Advanced Search & Filter) --}}
<div class="card mb-4">
    <div class="card-body">
        {{-- Form untuk pencarian dan filter, menggunakan metode GET dan mengarah ke route 'anggota.search' --}}
        <form action="{{ route('anggota.search') }}" method="GET">
            <div class="row g-3">
                {{-- Input Pencarian Keyword (Nama/Email/Telepon) --}}
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Pencarian</label>
                    <input type="text" name="keyword" class="form-control" 
                           value="{{ request('keyword') }}"
                           placeholder="Nama/email/telepon...">
                </div>
                
                {{-- Dropdown Filter: Jenis Kelamin --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">Semua</option>
                        <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                {{-- Dropdown Filter: Status --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                {{-- Dropdown Filter: Pekerjaan --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Pekerjaan</label>
                    <select name="pekerjaan" class="form-select">
                        <option value="">Semua</option>
                        <option value="Mahasiswa" {{ request('pekerjaan') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Pegawai" {{ request('pekerjaan') == 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="Wiraswasta" {{ request('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                    </select>
                </div>
                
                {{-- Input Filter: Rentang Umur (Min dan Max) --}}
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Rentang Umur</label>
                    <div class="input-group">
                        <input type="number" name="min_umur" class="form-control" placeholder="Min" value="{{ request('min_umur') }}">
                        <span class="input-group-text">-</span>
                        <input type="number" name="max_umur" class="form-control" placeholder="Max" value="{{ request('max_umur') }}">
                    </div>
                </div>
            </div>
            
            {{-- Tombol Aksi Filter --}}
            <div class="d-flex justify-content-end mt-3 gap-2">
                {{-- Tombol Reset Filter, mengarah ke index dengan parameter clear_filter --}}
                <a href="{{ route('anggota.index', ['clear_filter' => 1]) }}" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Reset
                </a>
                {{-- Tombol Submit untuk menerapkan filter --}}
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Bagian Statistik Singkat --}}
<div class="row mb-4">
    {{-- Card Statistik: Total Anggota --}}
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Anggota</h6>
                        {{-- Menampilkan variabel $totalAnggota yang dikirim dari controller --}}
                        <h2>{{ $totalAnggota }}</h2>
                    </div>
                    <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Card Statistik: Anggota Aktif --}}
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Anggota Aktif</h6>
                        {{-- Menampilkan variabel $anggotaAktif --}}
                        <h2>{{ $anggotaAktif }}</h2>
                    </div>
                    <i class="bi bi-person-check-fill text-primary" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Card Statistik: Anggota Nonaktif --}}
    <div class="col-md-4">
        <div class="card border-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Anggota Nonaktif</h6>
                        {{-- Menampilkan variabel $anggotaNonaktif --}}
                        <h2>{{ $anggotaNonaktif }}</h2>
                    </div>
                    <i class="bi bi-person-x-fill text-secondary" style="font-size: 3rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Data Anggota --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                {{-- Header Tabel --}}
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                {{-- Isi Tabel --}}
                <tbody>
                    {{-- Melakukan perulangan untuk setiap data di $anggotas --}}
                    @forelse ($anggotas as $anggota)
                    <tr>
                        {{-- Menampilkan nomor urut baris (berdasarkan perulangan) --}}
                        <td>{{ $loop->iteration }}</td>
                        {{-- Menampilkan Kode Anggota --}}
                        <td>
                            <code>{{ $anggota->kode_anggota }}</code>
                        </td>
                        {{-- Menampilkan Nama Anggota (cetak tebal) --}}
                        <td>
                            <strong>{{ $anggota->nama }}</strong>
                        </td>
                        {{-- Menampilkan Email Anggota dengan ikon --}}
                        <td>
                            <i class="bi bi-envelope"></i>
                            {{ $anggota->email }}
                        </td>
                        {{-- Menampilkan Nomor Telepon dengan ikon --}}
                        <td>
                            <i class="bi bi-telephone"></i>
                            {{ $anggota->telepon }}
                        </td>
                        {{-- Menampilkan Jenis Kelamin dengan ikon khusus berdasarkan nilai --}}
                        <td>
                            @if ($anggota->jenis_kelamin == 'Laki-laki')
                            <i class="bi bi-gender-male text-primary"></i>
                            @else
                            <i class="bi bi-gender-female text-danger"></i>
                            @endif
                            {{ $anggota->jenis_kelamin }}
                        </td>
                        {{-- Menampilkan Status Anggota menggunakan badge Bootstrap --}}
                        <td>
                            @if ($anggota->status == 'Aktif')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Aktif
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i> Nonaktif
                            </span>
                            @endif
                        </td>
                        {{-- Kolom Aksi: Tombol Detail, Hapus, Edit --}}
                        <td>
                            {{-- Membungkus tombol dalam button group --}}
                            <div class="btn-group" role="group">
                                {{-- Tombol Detail (Melihat data lengkap anggota) --}}
                                <a href="{{ route('anggota.show', $anggota->id) }}"
                                    class="btn btn-sm btn-info text-white"
                                    title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- Form Hapus: Mengirimkan method DELETE via spoofing untuk menghapus anggota --}}
                                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                        class="btn btn-sm btn-danger btn-delete-confirm" 
                                        data-confirm="Apakah Anda yakin ingin menghapus anggota {{ $anggota->nama }}?"
                                        title="Hapus"
                                        style="border-radius: 0;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                
                                {{-- Tombol Edit (Mengubah data anggota) --}}
                                <a href="{{ route('anggota.edit', $anggota->id) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Edit"
                                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    {{-- Menangani kondisi jika data anggota kosong --}}
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="bi bi-inbox"></i>
                            Tidak ada data anggota
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>