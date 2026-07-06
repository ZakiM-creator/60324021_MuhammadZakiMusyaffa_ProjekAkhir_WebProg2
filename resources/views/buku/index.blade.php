<x-app-layout theme="bootstrap" title="Daftar Buku">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <div>
        <a href="{{ route('buku.export') }}" class="btn btn-success me-2">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form Search & Filter Advanced --}}
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-search"></i> Pencarian & Filter Advanced
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('buku.search') }}" method="GET">
            <div class="row g-3">

                {{-- Input Keyword --}}
                <div class="col-md-6">
                    <label for="keyword" class="form-label">
                        <i class="bi bi-search"></i> Kata Kunci
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="keyword"
                        name="keyword"
                        placeholder="Cari judul, pengarang, atau penerbit..."
                        value="{{ request('keyword') }}">
                    <small class="text-muted">
                        Cari di judul, pengarang, dan penerbit
                    </small>
                </div>

                {{-- Filter Kategori --}}
                <div class="col-md-6">
                    <label for="kategori_id" class="form-label">
                        <i class="bi bi-tag"></i> Kategori
                    </label>
                    <select class="form-select" id="kategori_id" name="kategori_id">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoris ?? [] as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div class="col-md-4">
                    <label for="tahun" class="form-label">
                        <i class="bi bi-calendar"></i> Tahun Terbit
                    </label>
                    <select class="form-select" id="tahun" name="tahun">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($tahuns ?? [] as $thn)
                        <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                            {{ $thn }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Ketersediaan --}}
                <div class="col-md-4">
                    <label for="ketersediaan" class="form-label">
                        <i class="bi bi-box-seam"></i> Ketersediaan
                    </label>
                    <select class="form-select" id="ketersediaan" name="ketersediaan">
                        <option value="">-- Semua Status --</option>
                        <option value="1" {{ request('ketersediaan') == '1' ? 'selected' : '' }}>Tersedia (Stok > 0)</option>
                        <option value="0" {{ request('ketersediaan') == '0' ? 'selected' : '' }}>Habis (Stok = 0)</option>
                    </select>
                </div>

                {{-- Filter Harga --}}
                <div class="col-md-6 mt-3">
                    <label class="form-label">
                        <i class="bi bi-cash"></i> Range Harga (Rp)
                    </label>
                    <div class="input-group">
                        <input type="number" name="min_harga" class="form-control" placeholder="Min" value="{{ request('min_harga') }}">
                        <span class="input-group-text">-</span>
                        <input type="number" name="max_harga" class="form-control" placeholder="Max" value="{{ request('max_harga') }}">
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('buku.index', ['clear_filter' => 1]) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Terapkan Pencarian
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Info Filter Aktif --}}
@if(request()->hasAny(['keyword', 'kategori', 'tahun', 'ketersediaan']))
<div class="alert alert-info mb-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">
            <strong>Filter Aktif:</strong>

            {{-- Badge Keyword --}}
            @if(request('keyword'))
            <span class="badge bg-primary ms-1">
                <i class="bi bi-search"></i>
                Keyword: "{{ request('keyword') }}"
            </span>
            @endif

            {{-- Badge Kategori --}}
            @if(request('kategori'))
            <span class="badge bg-success ms-1">
                <i class="bi bi-tag"></i>
                Kategori: {{ request('kategori') }}
            </span>
            @endif

            {{-- Badge Tahun --}}
            @if(request('tahun'))
            <span class="badge bg-warning text-dark ms-1">
                <i class="bi bi-calendar"></i>
                Tahun: {{ request('tahun') }}
            </span>
            @endif

            {{-- Badge Ketersediaan --}}
            @if(request('ketersediaan'))
            <span class="badge bg-info text-dark ms-1">
                <i class="bi bi-box-seam"></i>
                Ketersediaan: {{ ucfirst(request('ketersediaan')) }}
            </span>
            @endif
        </div>

        {{-- Tombol Clear Filter --}}
        <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Hapus Filter
        </a>
    </div>
</div>
@endif


{{-- Filter Kategori --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">
            <i class="bi bi-funnel"></i> Filter Kategori:
        </h6>
        <div class="btn-group" role="group">
            <a href="{{ route('buku.index') }}" class="btn btn-sm {{ !request('kategori_id') && !isset($kategori_id) ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            @foreach(\App\Models\Kategori::all() as $kat)
            <a href="{{ route('buku.kategori', $kat->id) }}" class="btn btn-sm {{ (request('kategori_id') == $kat->id || (isset($kategori_id) && $kategori_id == $kat->id)) ? 'btn-' . $kat->warna : 'btn-outline-' . $kat->warna }}">
                {{ $kat->nama_kategori }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Form dan Header untuk Bulk Delete --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h5 class="mb-0">
            <i class="bi bi-book-half"></i> Daftar Buku
        </h5>
        <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm" enabled>
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div>
    <div class="card-body">
        <form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
        </form>

        {{-- Select All Checkbox --}}
        <div class="form-check mb-3 pb-3 border-bottom">
            <input class="form-check-input" type="checkbox" id="select-all">
            <label class="form-check-label fw-bold" for="select-all">
                Pilih Semua
            </label>
        </div>
    </div>
</div>

{{-- Daftar Buku --}}
@forelse ($bukus as $buku)
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            {{-- Checkbox untuk setiap buku --}}
            <div class="col-md-1 d-flex align-items-center">
                <div class="form-check">
                    <input class="form-check-input buku-checkbox" type="checkbox" 
                           name="buku_ids[]" value="{{ $buku->id }}" 
                           id="buku{{ $buku->id }}">
                </div>
            </div>

            <div class="col-md-2 text-center">
                <i class="bi bi-book text-primary mb-2" style="font-size: 3rem; display:block;"></i>
                
                {{-- Barcode Simulation (Bonus) --}}
                <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $buku->isbn ?? 'BK-'.str_pad($buku->id, 4, '0', STR_PAD_LEFT) }}&code=Code128&dpi=72" 
                     alt="Barcode {{ $buku->judul }}" 
                     class="img-fluid mb-2 border p-1 bg-white rounded"
                     style="max-height: 50px;">
                
                <div class="mt-1">
                    <span class="badge bg-{{ $buku->kategoriRel ? $buku->kategoriRel->warna : 'secondary' }}">
                        {{ $buku->kategoriRel ? $buku->kategoriRel->nama_kategori : '-' }}
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="card-title">
                    <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none fw-bold text-dark">
                        {{ $buku->judul }}
                    </a>
                </h5>

                <p class="card-text text-muted mb-2">
                    <i class="bi bi-person"></i> {{ $buku->pengarang }} |
                    <i class="bi bi-building"></i> {{ $buku->penerbit }} |
                    <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
                </p>

                @if ($buku->isbn)
                <p class="card-text small text-muted mb-1">
                    <i class="bi bi-upc"></i> ISBN: {{ $buku->isbn }}
                </p>
                @endif

                @if ($buku->deskripsi)
                <p class="card-text">
                    {{ Str::limit($buku->deskripsi, 150) }}
                </p>
                @endif
            </div>

            <div class="col-md-3 text-end">
                <h4 class="text-primary mb-2">
                    {{ $buku->harga_format }}
                </h4>

                <div class="mb-3">
                    @if ($buku->stok > 0)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Tersedia
                    </span>
                    <div class="text-muted small mt-1">
                        Stok: {{ $buku->stok }} buku
                    </div>
                    @else
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle"></i> Habis
                    </span>
                    @endif
                </div>

                <div class="btn-group-vertical d-grid gap-2">
                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    {{-- Delete Button dengan SweetAlert --}}
                    <form action="{{ route('buku.destroy', $buku->id) }}"
                        method="POST"
                        class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger w-100 btn-delete"
                            data-judul="{{ $buku->judul }}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>

                    @push('scripts')
                    <script>
                        // SweetAlert confirmation untuk delete
                        document.querySelectorAll('.btn-delete').forEach(button => {
                            button.addEventListener('click', function(e) {
                                e.preventDefault();
                                const form = this.closest('form');
                                const judul = this.getAttribute('data-judul');

                                Swal.fire({
                                    title: 'Konfirmasi Hapus',
                                    text: `Apakah Anda yakin ingin menghapus buku "${judul}"?`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        form.submit();
                                    }
                                });
                            });
                        });
                    </script>
                    @endpush

                    @push('scripts')
                    <script>
                        // Loading state saat submit form
                        document.querySelectorAll('form').forEach(form => {
                            form.addEventListener('submit', function() {
                                const submitBtn = this.querySelector('button[type="submit"]');
                                if (submitBtn && !this.classList.contains('delete-form')) {
                                    submitBtn.disabled = true;
                                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
                                }
                            });
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</div>

@empty
{{-- Pesan Jika Tidak Ada Hasil --}}
<div class="alert alert-warning">
    <div class="d-flex align-items-start">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-3"></i>
        <div>
            <h5 class="alert-heading mb-2">
                <strong>Tidak Ada Hasil Ditemukan</strong>
            </h5>

            {{-- Pesan berbeda berdasarkan kondisi --}}
            @if(request()->hasAny(['keyword', 'kategori', 'tahun', 'ketersediaan']))
            {{-- Jika ada filter aktif --}}
            <p class="mb-2">
                Tidak ada buku yang sesuai dengan kriteria pencarian Anda:
            </p>
            <ul class="mb-3">
                @if(request('keyword'))
                <li>Kata kunci: <strong>"{{ request('keyword') }}"</strong></li>
                @endif
                @if(request('kategori'))
                <li>Kategori: <strong>{{ request('kategori') }}</strong></li>
                @endif
                @if(request('tahun'))
                <li>Tahun: <strong>{{ request('tahun') }}</strong></li>
                @endif
                @if(request('ketersediaan'))
                <li>Ketersediaan: <strong>{{ ucfirst(request('ketersediaan')) }}</strong></li>
                @endif
            </ul>
            <p class="mb-0">
                <strong>Saran:</strong>
            </p>
            <ul class="mb-2">
                <li>Coba gunakan kata kunci yang berbeda</li>
                <li>Kurangi jumlah filter yang digunakan</li>
                <li>Periksa ejaan kata kunci Anda</li>
            </ul>
            <a href="{{ route('buku.index') }}" class="btn btn-sm btn-warning">
                <i class="bi bi-arrow-clockwise"></i> Reset & Lihat Semua Buku
            </a>
            @else
            {{-- Jika tidak ada filter (database kosong) --}}
            <p class="mb-2">
                Belum ada data buku di database.
            </p>
            <a href="{{ route('buku.create') }}" class="btn btn-sm btn-warning">
                <i class="bi bi-plus-circle"></i> Tambah Buku Pertama
            </a>
            @endif
        </div>
    </div>
</div>
@endforelse

@if ($bukus->count() > 0)
<div class="text-center mt-4">
    <p class="text-muted">
        Menampilkan {{ $bukus->count() }} buku
        @isset($kategori)
        dari kategori <strong>{{ $kategori }}</strong>
        @endisset
    </p>
</div>
@endif

@push('scripts')
<script>
    // Select All Checkbox Logic
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="buku_ids[]"]');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkDeleteBtn();
    });

    // Handle individual checkbox change
    document.querySelectorAll('input[name="buku_ids[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteBtn);
    });

    // Update tombol delete berdasarkan checkbox yang dicek
    function updateBulkDeleteBtn() {
        const checked = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
        const btn = document.getElementById('bulk-delete-btn');
        const selectAllCheckbox = document.getElementById('select-all');
        const totalCheckboxes = document.querySelectorAll('input[name="buku_ids[]"]').length;

        btn.disabled = checked === 0;
        btn.textContent = checked > 0 ? `Hapus ${checked} Buku (Terpilih)` : 'Hapus Terpilih';

        // Update select-all checkbox state
        if (checked === totalCheckboxes && totalCheckboxes > 0) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (checked > 0) {
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Handle Bulk Delete Button Click
    document.getElementById('bulk-delete-btn').addEventListener('click', function(e) {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;

        if (checkedCount === 0) {
            Swal.fire({
                title: 'Oops!',
                text: 'Pilih minimal 1 buku untuk dihapus',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus ${checkedCount} buku terpilih? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Copy selected checkboxes ke form dan submit
                const form = document.getElementById('bulk-delete-form');
                const selectedCheckboxes = document.querySelectorAll('input[name="buku_ids[]"]:checked');

                // Clear form dari input sebelumnya
                form.querySelectorAll('input[name="buku_ids[]"]').forEach(el => el.remove());

                // Add selected checkboxes ke form
                selectedCheckboxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'buku_ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });

                form.submit();
            }
        });
    });
</script>
@endpush

</x-app-layout>