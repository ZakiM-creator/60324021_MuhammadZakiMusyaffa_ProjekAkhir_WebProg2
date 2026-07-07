{{-- 
============================================================
FILE: transaksi/index.blade.php
FUNGSI: Halaman daftar transaksi peminjaman buku perpustakaan
============================================================
--}}
{{-- Memanggil komponen layout utama (app.blade.php) dengan tema bootstrap dan menetapkan judul halaman --}}
<x-app-layout theme="bootstrap" title="Daftar Transaksi Peminjaman">
{{-- Header halaman dan tombol tambah transaksi --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- Judul halaman --}}
    <h1>
        <i class="bi bi-arrow-left-right"></i>
        Daftar Transaksi Peminjaman
    </h1>
    {{-- Tombol untuk menuju form tambah transaksi (pinjam buku) --}}
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Pinjam Buku
    </a>
</div>

{{-- STATISTIK TRANSAKSI --}}
<div class="row mb-4">
    {{-- Card Total Transaksi --}}
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Transaksi</h6>
                {{-- Menampilkan total keseluruhan transaksi --}}
                <h2>{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    {{-- Card Transaksi Sedang Dipinjam --}}
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted">Sedang Dipinjam</h6>
                {{-- Menghitung transaksi dengan status "Dipinjam" --}}
                <h2>{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
            </div>
        </div>
    </div>
    {{-- Card Transaksi Sudah Dikembalikan --}}
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Sudah Dikembalikan</h6>
                {{-- Menghitung transaksi dengan status "Dikembalikan" --}}
                <h2>{{ $transaksis->where('status', 'Dikembalikan')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- ADVANCED SEARCH & FILTER --}}
<div class="card mb-4">
    <div class="card-body">
        {{-- Form GET untuk melakukan filter data transaksi --}}
        <form action="{{ route('transaksi.index') }}" method="GET">
            <div class="row g-3">
                {{-- Input filter rentang tanggal (Mulai) --}}
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tanggal Pinjam (Dari)</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                {{-- Input filter rentang tanggal (Sampai) --}}
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tanggal Pinjam (Sampai)</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
                {{-- Pilihan filter berdasarkan status --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                {{-- Pilihan filter berdasarkan anggota --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Anggota</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">Semua Anggota</option>
                        {{-- Looping daftar anggota untuk dropdown --}}
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Pilihan filter berdasarkan buku --}}
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Buku</label>
                    <select name="buku_id" class="form-select">
                        <option value="">Semua Buku</option>
                        {{-- Looping daftar buku untuk dropdown --}}
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ request('buku_id') == $buku->id ? 'selected' : '' }}>{{ $buku->judul }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Tombol aksi filter --}}
            <div class="d-flex justify-content-end mt-3 gap-2">
                {{-- Tombol reset untuk menghapus parameter GET (clear filter) --}}
                <a href="{{ route('transaksi.index', ['clear_filter' => 1]) }}" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Reset
                </a>
                {{-- Tombol submit filter --}}
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- TABEL TRANSAKSI --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                {{-- Header tabel --}}
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                {{-- Body tabel --}}
                <tbody>
                    {{-- Looping data transaksi, jika kosong tampilkan 'Belum ada transaksi' --}}
                    @forelse($transaksis as $transaksi)
                    <tr>
                        {{-- Menampilkan nomor urut baris (loop iteration) --}}
                        <td>{{ $loop->iteration }}</td>
                        {{-- Menampilkan kode transaksi dalam tag code --}}
                        <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                        {{-- Menampilkan nama anggota dari relasi --}}
                        <td>{{ $transaksi->anggota->nama }}</td>
                        {{-- Menampilkan judul buku dari relasi --}}
                        <td>{{ $transaksi->buku->judul }}</td>
                        {{-- Menampilkan tanggal pinjam --}}
                        <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        {{-- Menampilkan tanggal kembali (tenggat atau pengembalian sebenarnya) --}}
                        <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d M Y') : '-' }}</td>
                        {{-- Menampilkan badge status --}}
                        <td>
                            @if($transaksi->status == 'Dipinjam')
                                {{-- Mengecek apakah tanggal sekarang sudah melewati batas tanggal kembali (terlambat) --}}
                                @if(now()->startOfDay()->greaterThan($transaksi->tanggal_kembali))
                                    @php
                                        // Menghitung jumlah hari keterlambatan
                                        $hari = $transaksi->tanggal_kembali->diffInDays(now()->startOfDay());
                                    @endphp
                                    <span class="badge bg-danger">Terlambat ({{ $hari }} hari)</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @endif
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                        {{-- Kolom aksi --}}
                        <td>
                            {{-- Tombol lihat detail transaksi --}}
                            <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    {{-- Baris yang ditampilkan jika tidak ada data transaksi --}}
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>