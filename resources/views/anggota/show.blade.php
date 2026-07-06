<x-app-layout theme="bootstrap" :title="$anggota->nama">
<div class="row">
    <div class="col-12 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
                <li class="breadcrumb-item active">{{ $anggota->nama }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person"></i>
                    Detail Anggota
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-flex justify-content-center gap-4 align-items-center mb-3">
                        @if ($anggota->jenis_kelamin == 'Laki-laki')
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                        @else
                        <i class="bi bi-person-circle text-danger" style="font-size: 5rem;"></i>
                        @endif
                        
                        {{-- QR Code Simulation (Bonus) --}}
                        <div class="text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $anggota->kode_anggota }}" 
                                 alt="QR {{ $anggota->kode_anggota }}" 
                                 class="img-thumbnail p-1 bg-white">
                            <div class="small text-muted mt-1" style="font-size: 0.7rem;"><i class="bi bi-qr-code-scan"></i> Scan Me</div>
                        </div>
                    </div>
                    
                    <h3 class="mt-2">{{ $anggota->nama }}</h3>
                    @if ($anggota->status == 'Aktif')
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Anggota Aktif
                    </span>
                    @else
                    <span class="badge bg-secondary">
                        <i class="bi bi-x-circle"></i> Nonaktif
                    </span>
                    @endif
                </div>

                <table class="table table-borderless">
                    <tr>
                        <td width="200" class="fw-bold">
                            <i class="bi bi-upc text-success"></i> Kode Anggota
                        </td>
                        <td>: <code>{{ $anggota->kode_anggota }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-envelope text-success"></i> Email
                        </td>
                        <td>: {{ $anggota->email }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-telephone text-success"></i> Telepon
                        </td>
                        <td>: {{ $anggota->telepon }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-geo-alt text-success"></i> Alamat
                        </td>
                        <td>: {{ $anggota->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar text-success"></i> Tanggal Lahir
                        </td>
                        <td>: {{ $anggota->tanggal_lahir->format('d F Y') }} ({{ $anggota->umur }} tahun)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-gender-ambiguous text-success"></i> Jenis Kelamin
                        </td>
                        <td>: {{ $anggota->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-briefcase text-success"></i> Pekerjaan
                        </td>
                        <td>: {{ $anggota->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">
                            <i class="bi bi-calendar-check text-success"></i> Tanggal Daftar
                        </td>
                        <td>: {{ $anggota->tanggal_daftar->format('d F Y') }} ({{ $anggota->lama_anggota }} hari)</td>
                    </tr>
                </table>

                <hr>
                <div class="row text-muted small">
                    <div class="col-md-6">
                        <i class="bi bi-clock"></i>
                        Ditambahkan: {{ $anggota->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="col-md-6 text-end">
                        <i class="bi bi-clock-history"></i>
                        Terakhir Update: {{ $anggota->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Aksi
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Anggota
                </a>
                <a href="{{ route('anggota.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <hr>
                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger w-100 btn-delete-confirm" data-confirm="Anggota '{{ $anggota->nama }}' akan dihapus secara permanen dari sistem!">
                        <i class="bi bi-trash"></i> Hapus Anggota
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Peminjaman Anggota --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary"></i> Riwayat Peminjaman</h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2 p-2">Total: {{ $totalPinjam }}</span>
                    <span class="badge bg-danger p-2">Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('anggota.show', $anggota->id) }}" method="GET" class="mb-3 d-flex gap-2 w-50">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    @if(request('status'))
                        <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $trx)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $trx->buku->judul }}</div>
                                        <small class="text-muted">{{ $trx->kode_transaksi }}</small>
                                    </td>
                                    <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $trx->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td>
                                        @if($trx->status === 'Dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($trx->denda, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>