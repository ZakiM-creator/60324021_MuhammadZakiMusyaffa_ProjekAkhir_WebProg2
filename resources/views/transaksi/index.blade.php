<x-app-layout theme="bootstrap" title="Daftar Transaksi Peminjaman">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-arrow-left-right"></i>
        Daftar Transaksi Peminjaman
    </h1>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Pinjam Buku
    </a>
</div>

{{-- Statistik --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Transaksi</h6>
                <h2>{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted">Sedang Dipinjam</h6>
                <h2>{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Sudah Dikembalikan</h6>
                <h2>{{ $transaksis->where('status', 'Dikembalikan')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Advanced Search & Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tanggal Pinjam (Dari)</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">Tanggal Pinjam (Sampai)</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Anggota</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">Semua Anggota</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small mb-1">Buku</label>
                    <select name="buku_id" class="form-select">
                        <option value="">Semua Buku</option>
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ request('buku_id') == $buku->id ? 'selected' : '' }}>{{ $buku->judul }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2">
                <a href="{{ route('transaksi.index', ['clear_filter' => 1]) }}" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Reset
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                <tbody>
                    @forelse($transaksis as $transaksi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                        <td>{{ $transaksi->anggota->nama }}</td>
                        <td>{{ $transaksi->buku->judul }}</td>
                        <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        <td>{{ $transaksi->tanggal_kembali ? $transaksi->tanggal_kembali->format('d M Y') : '-' }}</td>
                        <td>
                            @if($transaksi->status == 'Dipinjam')
                                @if(now()->startOfDay()->greaterThan($transaksi->tanggal_kembali))
                                    @php
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
                        <td>
                            <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
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