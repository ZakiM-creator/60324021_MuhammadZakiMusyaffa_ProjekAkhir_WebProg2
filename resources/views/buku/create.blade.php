{{-- Menggunakan komponen layout 'app' dengan tema bootstrap dan menetapkan judul halaman "Tambah Buku" --}}
<x-app-layout theme="bootstrap" title="Tambah Buku">
{{-- Baris utama grid Bootstrap untuk meratakan konten di tengah --}}
<div class="row justify-content-center">
    {{-- Mengatur lebar form (10 kolom di perangkat medium ke atas) --}}
    <div class="col-md-10">
        {{-- Card sebagai pembungkus utama form --}}
        <div class="card">
            {{-- Header card dengan latar belakang biru (primary) dan teks putih --}}
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    {{-- Ikon plus circle untuk merepresentasikan penambahan data --}}
                    <i class="bi bi-plus-circle"></i>
                    Tambah Buku Baru
                </h4>
            </div>
            
            {{-- Bagian isi (body) card yang memuat form input --}}
            <div class="card-body">
                {{-- Form untuk menambahkan buku baru. Action diarahkan ke rute 'buku.store' menggunakan POST, enctype dipasang untuk berjaga-jaga jika ada input file --}}
                <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                    {{-- Token CSRF wajib untuk keamanan setiap form POST di Laravel --}}
                    @csrf

                    {{-- Baris Pertama form: Kode Buku dan Judul --}}
                    <div class="row">
                        {{-- Kolom Input: Kode Buku --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label input untuk kode buku --}}
                            <label for="kode_buku" class="form-label">
                                Kode Buku <span class="text-danger">*</span>
                            </label>
                            {{-- Field input teks untuk kode buku --}}
                            <input type="text"
                                name="kode_buku"
                                id="kode_buku"
                                class="form-control @error('kode_buku') is-invalid @enderror"
                                value="{{ old('kode_buku') }}"
                                placeholder="Contoh: BK-PROG-X  001">
                            {{-- Menampilkan pesan error validasi jika ada --}}
                            @error('kode_buku')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Judul Buku --}}
                        <div class="col-md-8 mb-3">
                            {{-- Label input untuk judul buku --}}
                            <label for="judul" class="form-label">
                                Judul Buku <span class="text-danger">*</span>
                            </label>
                            {{-- Field input teks untuk judul buku --}}
                            <input type="text"
                                name="judul"
                                id="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul') }}"
                                placeholder="Masukkan judul buku">
                            {{-- Menampilkan pesan error validasi judul --}}
                            @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris Kedua form: Kategori, Pengarang, Penerbit --}}
                    <div class="row">
                        {{-- Kolom Input: Kategori --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk dropdown kategori --}}
                            <label for="kategori_id" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan kategori dari data yang dikirim oleh Controller --}}
                            <select name="kategori_id"
                                id="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Melakukan perulangan untuk setiap data kategori dan menampilkannya sebagai opsi --}}
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Menampilkan pesan error validasi kategori_id --}}
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Pengarang --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk input nama pengarang --}}
                            <label for="pengarang" class="form-label">
                                Pengarang <span class="text-danger">*</span>
                            </label>
                            {{-- Field input teks untuk nama pengarang --}}
                            <input type="text"
                                name="pengarang"
                                id="pengarang"
                                class="form-control @error('pengarang') is-invalid @enderror"
                                value="{{ old('pengarang') }}"
                                placeholder="Nama pengarang">
                            {{-- Pesan error untuk validasi pengarang --}}
                            @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Penerbit --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk input nama penerbit --}}
                            <label for="penerbit" class="form-label">
                                Penerbit <span class="text-danger">*</span>
                            </label>
                            {{-- Field input teks untuk nama penerbit --}}
                            <input type="text"
                                name="penerbit"
                                id="penerbit"
                                class="form-control @error('penerbit') is-invalid @enderror"
                                value="{{ old('penerbit') }}"
                                placeholder="Nama penerbit">
                            {{-- Pesan error untuk validasi penerbit --}}
                            @error('penerbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris Ketiga form: Tahun Terbit, ISBN, Bahasa, Harga, Stok --}}
                    <div class="row">
                        {{-- Kolom Input: Tahun Terbit --}}
                        <div class="col-md-3 mb-3">
                            {{-- Label untuk input tahun terbit --}}
                            <label for="tahun_terbit" class="form-label">
                                Tahun Terbit <span class="text-danger">*</span>
                            </label>
                            {{-- Field input number untuk tahun, default-nya tahun saat ini --}}
                            <input type="number"
                                name="tahun_terbit"
                                id="tahun_terbit"
                                class="form-control @error('tahun_terbit') is-invalid @enderror"
                                value="{{ old('tahun_terbit', date('Y')) }}"
                                min="1900"
                                max="{{ date('Y') }}">
                            {{-- Pesan error untuk tahun terbit --}}
                            @error('tahun_terbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: ISBN --}}
                        <div class="col-md-3 mb-3">
                            {{-- Label untuk input ISBN --}}
                            <label for="isbn" class="form-label">
                                ISBN
                            </label>
                            {{-- Field input teks untuk ISBN buku --}}
                            <input type="text"
                                name="isbn"
                                id="isbn"
                                class="form-control @error('isbn') is-invalid @enderror"
                                value="{{ old('isbn') }}"
                                placeholder="978-xxx-xxx">
                            {{-- Pesan error untuk ISBN --}}
                            @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Bahasa --}}
                        <div class="col-md-2 mb-3">
                            {{-- Label untuk dropdown bahasa --}}
                            <label for="bahasa" class="form-label">
                                Bahasa <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan bahasa (Indonesia/Inggris) --}}
                            <select name="bahasa"
                                id="bahasa"
                                class="form-select @error('bahasa') is-invalid @enderror">
                                <option value="Indonesia" {{ old('bahasa', 'Indonesia') == 'Indonesia' ? 'selected' : '' }}>
                                    Indonesia
                                </option>
                                <option value="Inggris" {{ old('bahasa') == 'Inggris' ? 'selected' : '' }}>
                                    Inggris
                                </option>
                            </select>
                            {{-- Pesan error untuk bahasa --}}
                            @error('bahasa')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Harga --}}
                        <div class="col-md-2 mb-3">
                            {{-- Label untuk input harga --}}
                            <label for="harga" class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>
                            {{-- Field input number untuk harga (nilai minimal 0) --}}
                            <input type="number"
                                name="harga"
                                id="harga"
                                class="form-control @error('harga') is-invalid @enderror"
                                value="{{ old('harga', 0) }}"
                                min="0"
                                step="1000">
                            {{-- Pesan error untuk validasi harga --}}
                            @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Stok --}}
                        <div class="col-md-2 mb-3">
                            {{-- Label untuk input stok --}}
                            <label for="stok" class="form-label">
                                Stok <span class="text-danger">*</span>
                            </label>
                            {{-- Field input number untuk stok barang/buku --}}
                            <input type="number"
                                name="stok"
                                id="stok"
                                class="form-control @error('stok') is-invalid @enderror"
                                value="{{ old('stok', 0) }}"
                                min="0">
                            {{-- Pesan error validasi untuk stok --}}
                            @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris Keempat form: Deskripsi Buku --}}
                    <div class="mb-3">
                        {{-- Label untuk input deskripsi --}}
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        {{-- Field textarea untuk deskripsi panjang (opsional) --}}
                        <textarea name="deskripsi"
                            id="deskripsi"
                            rows="4"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Deskripsi singkat tentang buku (opsional)">{{ old('deskripsi') }}</textarea>
                        {{-- Pesan error untuk validasi deskripsi --}}
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Garis pembatas sebelum kelompok tombol --}}
                    <hr>

                    {{-- Kelompok Tombol Aksi --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol Batal/Kembali ke halaman Daftar Buku --}}
                        <a href="{{ route('buku.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        {{-- Tombol Submit form untuk menyimpan data --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Menambahkan skrip Javascript khusus ke stack 'scripts' pada layout utama --}}
@push('scripts')
<script>
    // DEBUG: Mengecek action form sebelum proses submit terjadi, berguna untuk memonitor behavior
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Form submit event fired');      // Menandakan event trigger berjalan
        console.log('Form action:', this.action);    // Menampilkan URL tujuan action form ke console
        console.log('Form method:', this.method);    // Menampilkan method HTTP
        console.log('Current URL:', window.location.href); // Menampilkan lokasi URL halaman saat ini
    });
    
    // Auto format input harga (Membersihkan karakter non-digit) saat kolom kehilangan fokus (blur event)
    document.getElementById('harga').addEventListener('blur', function() {
        // Hanya membiarkan karakter angka yang ada di dalam input
        let value = this.value.replace(/\D/g, '');
        this.value = value;
    });
</script>
@endpush
</x-app-layout>