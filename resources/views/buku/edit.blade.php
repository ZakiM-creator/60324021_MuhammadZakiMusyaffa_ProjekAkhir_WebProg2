{{-- Menggunakan komponen layout utama 'app' dengan tema bootstrap dan menetapkan judul halaman --}}
<x-app-layout theme="bootstrap" title="Edit Buku">
{{-- Baris untuk menempatkan konten di tengah layar --}}
<div class="row justify-content-center">
    {{-- Lebar kolom form diatur menjadi 10 kolom pada ukuran layar medium ke atas --}}
    <div class="col-md-10">
        {{-- Card sebagai kontainer utama form edit --}}
        <div class="card">
            {{-- Header card dengan warna latar kuning (warning) menandakan mode pengeditan --}}
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Buku: {{ $buku->judul }}
                </h4>
            </div>
            
            {{-- Area isi dari form edit --}}
            <div class="card-body">
                {{-- Form diarahkan ke route update buku dengan method POST --}}
                <form action="{{ route('buku.update', $buku->id) }}" method="POST">
                    {{-- Token CSRF untuk keamanan form --}}
                    @csrf
                    {{-- Method spoofing: mengubah method form menjadi PUT sesuai dengan standard RESTful controller --}}
                    @method('PUT')

                    {{-- Baris pertama: Kode Buku dan Judul --}}
                    <div class="row">
                        {{-- Kolom Kode Buku --}}
                        <div class="col-md-4 mb-3">
                            <label for="kode_buku" class="form-label">
                                Kode Buku <span class="text-danger">*</span>
                            </label>
                            {{-- Field input kode buku, akan menampilkan nilai lama jika ada error, atau nilai dari database --}}
                            <input type="text"
                                name="kode_buku"
                                id="kode_buku"
                                class="form-control @error('kode_buku') is-invalid @enderror"
                                value="{{ old('kode_buku', $buku->kode_buku) }}">
                            {{-- Menampilkan pesan error validasi kode buku --}}
                            @error('kode_buku')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Judul --}}
                        <div class="col-md-8 mb-3">
                            <label for="judul" class="form-label">
                                Judul Buku <span class="text-danger">*</span>
                            </label>
                            {{-- Field input untuk judul buku --}}
                            <input type="text"
                                name="judul"
                                id="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $buku->judul) }}">
                            {{-- Menampilkan pesan error validasi judul --}}
                            @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris kedua: Kategori, Pengarang, dan Penerbit --}}
                    <div class="row">
                        {{-- Kolom Kategori --}}
                        <div class="col-md-4 mb-3">
                            <label for="kategori_id" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan kategori --}}
                            <select name="kategori_id"
                                id="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Melakukan iterasi untuk setiap kategori --}}
                                @foreach($kategoris as $kat)
                                {{-- Menandai kategori sebagai terpilih (selected) jika cocok dengan data sebelumnya atau data di database --}}
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $buku->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                            {{-- Pesan error untuk validasi kategori_id --}}
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Pengarang --}}
                        <div class="col-md-4 mb-3">
                            <label for="pengarang" class="form-label">
                                Pengarang <span class="text-danger">*</span>
                            </label>
                            {{-- Field input untuk nama pengarang --}}
                            <input type="text"
                                name="pengarang"
                                id="pengarang"
                                class="form-control @error('pengarang') is-invalid @enderror"
                                value="{{ old('pengarang', $buku->pengarang) }}">
                            {{-- Pesan error validasi pengarang --}}
                            @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Penerbit --}}
                        <div class="col-md-4 mb-3">
                            <label for="penerbit" class="form-label">
                                Penerbit <span class="text-danger">*</span>
                            </label>
                            {{-- Field input untuk nama penerbit --}}
                            <input type="text"
                                name="penerbit"
                                id="penerbit"
                                class="form-control @error('penerbit') is-invalid @enderror"
                                value="{{ old('penerbit', $buku->penerbit) }}">
                            {{-- Pesan error validasi penerbit --}}
                            @error('penerbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris ketiga: Tahun Terbit, ISBN, Bahasa, Harga, Stok --}}
                    <div class="row">
                        {{-- Kolom Tahun Terbit --}}
                        <div class="col-md-3 mb-3">
                            <label for="tahun_terbit" class="form-label">
                                Tahun Terbit <span class="text-danger">*</span>
                            </label>
                            {{-- Field input angka untuk tahun terbit --}}
                            <input type="number"
                                name="tahun_terbit"
                                id="tahun_terbit"
                                class="form-control @error('tahun_terbit') is-invalid @enderror"
                                value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                min="1900"
                                max="{{ date('Y') }}">
                            {{-- Pesan error validasi tahun_terbit --}}
                            @error('tahun_terbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom ISBN --}}
                        <div class="col-md-3 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            {{-- Field input teks untuk ISBN --}}
                            <input type="text"
                                name="isbn"
                                id="isbn"
                                class="form-control @error('isbn') is-invalid @enderror"
                                value="{{ old('isbn', $buku->isbn) }}">
                            {{-- Pesan error validasi ISBN --}}
                            @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Bahasa --}}
                        <div class="col-md-2 mb-3">
                            <label for="bahasa" class="form-label">
                                Bahasa <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan bahasa --}}
                            <select name="bahasa"
                                id="bahasa"
                                class="form-select @error('bahasa') is-invalid @enderror">
                                <option value="Indonesia" {{ old('bahasa', $buku->bahasa) == 'Indonesia' ? 'selected' : '' }}>
                                    Indonesia
                                </option>
                                <option value="Inggris" {{ old('bahasa', $buku->bahasa) == 'Inggris' ? 'selected' : '' }}>
                                    Inggris
                                </option>
                            </select>
                            {{-- Pesan error validasi bahasa --}}
                            @error('bahasa')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Harga --}}
                        <div class="col-md-2 mb-3">
                            <label for="harga" class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>
                            {{-- Field input angka untuk harga barang --}}
                            <input type="number"
                                name="harga"
                                id="harga"
                                class="form-control @error('harga') is-invalid @enderror"
                                value="{{ old('harga', $buku->harga) }}"
                                min="0"
                                step="1000">
                            {{-- Pesan error validasi harga --}}
                            @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Stok --}}
                        <div class="col-md-2 mb-3">
                            <label for="stok" class="form-label">
                                Stok <span class="text-danger">*</span>
                            </label>
                            {{-- Field input angka untuk stok --}}
                            <input type="number"
                                name="stok"
                                id="stok"
                                class="form-control @error('stok') is-invalid @enderror"
                                value="{{ old('stok', $buku->stok) }}"
                                min="0">
                            {{-- Pesan error validasi stok --}}
                            @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris keempat: Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        {{-- Field textarea untuk penjelasan buku --}}
                        <textarea name="deskripsi"
                            id="deskripsi"
                            rows="4"
                            class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        {{-- Pesan error validasi deskripsi --}}
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Garis Pemisah --}}
                    <hr>

                    {{-- Bagian Tombol Form --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol untuk membatalkan edit dan kembali ke halaman detail --}}
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        {{-- Tombol submit untuk mengupdate data --}}
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card Info Metadata Sistem --}}
        <div class="card mt-3">
            <div class="card-body">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong><br />
                    - Buku ditambahkan: {{ $buku->created_at->format('d M Y H:i') }}<br />
                    - Terakhir diupdate: {{ $buku->updated_at->format('d M Y H:i') }}
                </small>
            </div>
        </div>
    </div>
</div>
</x-app-layout>