<x-app-layout theme="bootstrap" :title="'Detail Transaksi ' . $transaksi->kode_transaksi">
    <div class="row">
        {{-- Breadcrumb --}}
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active">{{ $transaksi->kode_transaksi }}</li>
                </ol>
            </nav>
        </div>
    </div>



    @if($transaksi->status === 'Dipinjam' && now()->startOfDay()->greaterThan($transaksi->tanggal_kembali))
    @php
    $keterlambatan = $transaksi->tanggal_kembali->diffInDays(now()->startOfDay());
    @endphp
    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
        <div>
            <strong>Peringatan Terlambat!</strong> Buku ini belum dikembalikan dan telah melewati tenggat pengembalian selama <strong>{{ $keterlambatan }} hari</strong>.
        </div>
    </div>
    @endif

    <div class="row">
        {{-- Detail Transaksi --}}
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-1"></i> Rincian Transaksi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <th width="35%">Kode Transaksi</th>
                            <td>: <strong class="text-primary fs-5">{{ $transaksi->kode_transaksi }}</strong></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:
                                @if(in_array($transaksi->status, ['Pinjam', 'Dipinjam']))
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-clock me-1"></i> Dipinjam
                                </span>
                                @else
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i> Dikembalikan
                                </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>: {{ $transaksi->tanggal_pinjam->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Batas Kembali</th>
                            <td>: {{ $transaksi->tanggal_kembali->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dikembalikan</th>
                            <td>:
                                @if($transaksi->tanggal_dikembalikan)
                                {{ $transaksi->tanggal_dikembalikan->format('d F Y') }}
                                @else
                                <span class="text-muted italic">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Denda</th>
                            <td>:
                                @if($transaksi->denda > 0)
                                <span class="text-danger fw-bold fs-5">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                                @elseif(in_array($transaksi->status, ['Pinjam', 'Dipinjam']) && now()->startOfDay()->greaterThan($transaksi->tanggal_kembali))
                                @php
                                $keterlambatan = $transaksi->tanggal_kembali->diffInDays(now()->startOfDay());
                                $estimasiDenda = $keterlambatan * 5000;
                                @endphp
                                <span class="text-danger fw-bold">
                                    Rp {{ number_format($estimasiDenda, 0, ',', '.') }}
                                    <small class="text-muted fw-normal">(Estimasi terlambat {{ $keterlambatan }} hari)</small>
                                </span>
                                @else
                                <span class="text-success fw-bold">Rp 0 (Tidak ada denda)</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke List
                        </a>

                        @if($transaksi->status === 'Dipinjam')
                        <button type="button" class="btn btn-success" id="btn-kembalikan">
                            <i class="bi bi-arrow-return-left"></i> Kembalikan Buku
                        </button>

                        <form id="form-kembalikan" action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('PATCH')
                        </form>
                        @else
                        @if($transaksi->tanggal_dikembalikan <= $transaksi->tanggal_kembali)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> Dikembalikan tepat waktu pada
                                {{ $transaksi->tanggal_dikembalikan->format('d M Y') }}
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Terlambat dikembalikan!
                                Denda: Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                            </div>
                            @endif
                            @endif

                            @push('scripts')
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                document.getElementById('btn-kembalikan')?.addEventListener('click', function() {
                                    Swal.fire({
                                        title: 'Konfirmasi Pengembalian',
                                        text: 'Apakah Anda yakin ingin mengembalikan buku ini?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#198754',
                                        confirmButtonText: 'Ya, Kembalikan!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('form-kembalikan').submit();
                                        }
                                    });
                                });
                            </script>
                            @endpush
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Anggota & Buku --}}
        <div class="col-md-5 mb-4">
            <div class="row g-4">
                {{-- Detail Anggota --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-1"></i> Data Anggota
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="35%">Kode</th>
                                    <td>: {{ $transaksi->anggota->kode_anggota ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>: <strong>{{ $transaksi->anggota->nama ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $transaksi->anggota->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>: {{ $transaksi->anggota->telepon ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Detail Buku --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-book me-1"></i> Data Buku
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="35%">Kode</th>
                                    <td>: {{ $transaksi->buku->kode_buku ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Judul</th>
                                    <td>: <strong>{{ $transaksi->buku->judul ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Pengarang</th>
                                    <td>: {{ $transaksi->buku->pengarang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>: {{ $transaksi->buku->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Stok Tersedia</th>
                                    <td>: {{ $transaksi->buku->stok ?? 0 }} eks</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>