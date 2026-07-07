{{-- 
============================================================
FILE: kategori/edit.blade.php
FUNGSI: Menampilkan form untuk mengupdate data kategori yang sudah ada
============================================================
--}}
<x-app-layout theme="bootstrap" title="Edit Kategori">
    {{-- Header Judul & Breadcrumb --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Manajemen Kategori</a></li>
                    <li class="breadcrumb-item active">Edit Kategori</li>
                </ol>
            </nav>
            <h2 class="mb-0">
                <i class="bi bi-pencil-square text-primary"></i> Edit Kategori
            </h2>
        </div>
    </div>

    {{-- Kotak Form --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Form action diarahkan ke kategori.update dan wajib menyertakan ID (kategori->id) --}}
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                {{-- Method HTML native form hanya mendukung GET/POST. Karena update menggunakan PUT, kita 'tipu' lewat @method('PUT') --}}
                @method('PUT')
                
                {{-- INPUT: Nama Kategori --}}
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    {{-- Value mengambil old() input dulu (jika form gagal submit), atau data database ($kategori->nama_kategori) jika tidak --}}
                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}">
                    @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- INPUT: Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- INPUT: Warna --}}
                <div class="mb-3">
                    <label for="warna" class="form-label">Warna Label <span class="text-danger">*</span></label>
                    <select class="form-select @error('warna') is-invalid @enderror" id="warna" name="warna">
                        {{-- Logika pengecekan dropdown yang aktif. Jika data database cocok dengan value option, set selected --}}
                        <option value="primary" {{ old('warna', $kategori->warna) == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
                        <option value="success" {{ old('warna', $kategori->warna) == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
                        <option value="info" {{ old('warna', $kategori->warna) == 'info' ? 'selected' : '' }}>Cyan (Info)</option>
                        <option value="warning" {{ old('warna', $kategori->warna) == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
                        <option value="danger" {{ old('warna', $kategori->warna) == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
                        <option value="secondary" {{ old('warna', $kategori->warna) == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
                        <option value="dark" {{ old('warna', $kategori->warna) == 'dark' ? 'selected' : '' }}>Hitam (Dark)</option>
                    </select>
                    @error('warna')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TOMBOL SIMPAN --}}
                <div class="text-end mt-4">
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
