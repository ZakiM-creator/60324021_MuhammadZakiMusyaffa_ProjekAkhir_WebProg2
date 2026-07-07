{{-- 
============================================================
FILE: kategori/create.blade.php
FUNGSI: Menampilkan form untuk menambahkan data kategori baru
============================================================
--}}
<x-app-layout theme="bootstrap" title="Tambah Kategori">
    {{-- Header Judul & Breadcrumb (Navigasi Jejak) --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Manajemen Kategori</a></li>
                    <li class="breadcrumb-item active">Tambah Kategori</li>
                </ol>
            </nav>
            <h2 class="mb-0">
                <i class="bi bi-plus-circle text-primary"></i> Tambah Kategori
            </h2>
        </div>
    </div>

    {{-- Kotak utama berisi Form Input --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Form mengirim request POST ke route kategori.store --}}
            <form action="{{ route('kategori.store') }}" method="POST">
                {{-- Token CSRF (Wajib untuk semua form metode POST di Laravel) --}}
                @csrf
                
                {{-- INPUT: Nama Kategori --}}
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    {{-- Value old('nama_kategori') menjaga isian tidak hilang saat validasi gagal --}}
                    {{-- @error mengecek apakah ada pesan error, jika ada tambah class 'is-invalid' agar border merah --}}
                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}">
                    {{-- Menampilkan pesan error validasi (jika ada) --}}
                    @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- INPUT: Deskripsi Kategori --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- INPUT: Warna Label (Dropdown Select) --}}
                <div class="mb-3">
                    <label for="warna" class="form-label">Warna Label <span class="text-danger">*</span></label>
                    <select class="form-select @error('warna') is-invalid @enderror" id="warna" name="warna">
                        {{-- Logika ternary 'selected' digunakan untuk mengingat pilihan dropdown jika error validasi terjadi --}}
                        <option value="primary" {{ old('warna') == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
                        <option value="success" {{ old('warna') == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
                        <option value="info" {{ old('warna') == 'info' ? 'selected' : '' }}>Cyan (Info)</option>
                        <option value="warning" {{ old('warna') == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
                        <option value="danger" {{ old('warna') == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
                        <option value="secondary" {{ old('warna') == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
                        <option value="dark" {{ old('warna') == 'dark' ? 'selected' : '' }}>Hitam (Dark)</option>
                    </select>
                    @error('warna')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TOMBOL AKSI BAWAH --}}
                <div class="text-end mt-4">
                    {{-- Batal mengembalikan user ke halaman list index --}}
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary me-2">Batal</a>
                    {{-- Submit mengirimkan data form --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
