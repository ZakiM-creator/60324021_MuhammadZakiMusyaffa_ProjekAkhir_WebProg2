{{-- Menggunakan komponen layout 'app' dengan tema bootstrap dan menetapkan judul halaman "Tambah Anggota" --}}
<x-app-layout theme="bootstrap" title="Tambah Anggota">

{{-- Mendorong stylesheet tambahan ke dalam stack 'styles' --}}
@push('styles')
{{-- Memuat file CSS Flatpickr dari CDN untuk styling input kalender --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

{{-- Container utama menggunakan grid system Bootstrap, dipusatkan di tengah halaman --}}
<div class="row justify-content-center">
    <div class="col-md-10">
        {{-- Card sebagai pembungkus utama form --}}
        <div class="card">
            {{-- Header card dengan latar belakang hijau (success) dan teks putih --}}
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    {{-- Ikon tambah anggota --}}
                    <i class="bi bi-person-plus"></i>
                    Tambah Anggota Baru
                </h4>
            </div>
            
            {{-- Bagian isi dari card yang memuat formulir --}}
            <div class="card-body">
                {{-- Form untuk mengirim data anggota baru ke rute 'anggota.store' dengan metode POST --}}
                <form action="{{ route('anggota.store') }}" method="POST" novalidate>
                    {{-- Token CSRF untuk perlindungan dari serangan Cross-Site Request Forgery --}}
                    @csrf

                    {{-- Baris pertama: Kode Anggota dan Nama Lengkap --}}
                    <div class="row">
                        {{-- Kolom Input: Kode Anggota --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label input kode anggota --}}
                            <label for="kode_anggota" class="form-label">
                                Kode Anggota <span class="text-danger">*</span>
                            </label>
                            {{-- Input text untuk kode_anggota, otomatis diisi dari controller dan bersifat readonly --}}
                            <input type="text"
                                name="kode_anggota"
                                id="kode_anggota"
                                class="form-control @error('kode_anggota') is-invalid @enderror"
                                value="{{ old('kode_anggota', $kodeAnggota) }}"
                                readonly>
                            {{-- Pesan error jika validasi kode_anggota gagal --}}
                            @error('kode_anggota')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Panduan format kode anggota --}}
                            <small class="text-muted">Format: AGT-[TAHUN]-[NOMOR_URUT]</small>
                        </div>

                        {{-- Kolom Input: Nama Lengkap --}}
                        <div class="col-md-8 mb-3">
                            {{-- Label input nama --}}
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            {{-- Input text untuk nama lengkap anggota --}}
                            <input type="text"
                                name="nama"
                                id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}"
                                placeholder="Nama lengkap anggota">
                            {{-- Pesan error jika validasi nama gagal --}}
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris kedua: Email dan Nomor Telepon --}}
                    <div class="row">
                        {{-- Kolom Input: Email --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label input email --}}
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            {{-- Input type email untuk alamat email --}}
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="email@example.com">
                            {{-- Pesan error jika validasi email gagal --}}
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Nomor Telepon --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label input telepon --}}
                            <label for="telepon" class="form-label">
                                Nomor Telepon <span class="text-danger">*</span>
                            </label>
                            {{-- Input text untuk nomor telepon --}}
                            <input type="text"
                                name="telepon"
                                id="telepon"
                                class="form-control @error('telepon') is-invalid @enderror"
                                value="{{ old('telepon') }}"
                                placeholder="081234567890">
                            {{-- Pesan error jika validasi telepon gagal --}}
                            @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Petunjuk format input telepon --}}
                            <small class="text-muted">Format: 08xxxxxxxxxx atau +628xxxxxxxxxx</small>
                        </div>
                    </div>

                    {{-- Kolom Input: Alamat Lengkap (mengambil 1 baris penuh) --}}
                    <div class="mb-3">
                        {{-- Label input alamat --}}
                        <label for="alamat" class="form-label">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        {{-- Textarea untuk alamat --}}
                        <textarea name="alamat"
                            id="alamat"
                            rows="3"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Alamat lengkap dengan kota dan kode pos">{{ old('alamat') }}</textarea>
                        {{-- Pesan error jika validasi alamat gagal --}}
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Baris ketiga: Tanggal Lahir, Jenis Kelamin, dan Pekerjaan --}}
                    <div class="row">
                        {{-- Kolom Input: Tanggal Lahir --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label input tanggal lahir --}}
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            {{-- Input date untuk tanggal lahir, menggunakan batas maksimal hari ini --}}
                            <input type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir') }}"
                                max="{{ date('Y-m-d') }}"
                                placeholder="Pilih Tanggal Lahir">
                            {{-- Pesan error jika validasi tanggal_lahir gagal --}}
                            @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Jenis Kelamin --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label dropdown jenis kelamin --}}
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan jenis kelamin --}}
                            <select name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                {{-- Menampilkan opsi 'Laki-laki', menjaga state terpilih sebelumnya jika ada (old value) --}}
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                {{-- Menampilkan opsi 'Perempuan', menjaga state terpilih sebelumnya jika ada --}}
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                            {{-- Pesan error jika validasi jenis kelamin gagal --}}
                            @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Pekerjaan --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label input pekerjaan --}}
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            {{-- Input text untuk pekerjaan --}}
                            <input type="text"
                                name="pekerjaan"
                                id="pekerjaan"
                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                value="{{ old('pekerjaan') }}"
                                placeholder="Contoh: Mahasiswa, Pegawai, dll">
                            {{-- Pesan error jika validasi pekerjaan gagal --}}
                            @error('pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris keempat: Tanggal Daftar dan Status --}}
                    <div class="row">
                        {{-- Kolom Input: Tanggal Daftar --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label input tanggal daftar --}}
                            <label for="tanggal_daftar" class="form-label">
                                Tanggal Pendaftaran <span class="text-danger">*</span>
                            </label>
                            {{-- Input date untuk tanggal daftar, terisi otomatis tanggal hari ini --}}
                            <input type="date"
                                name="tanggal_daftar"
                                id="tanggal_daftar"
                                class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                value="{{ old('tanggal_daftar', date('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}"
                                placeholder="Pilih Tanggal Daftar">
                            {{-- Pesan error jika validasi tanggal daftar gagal --}}
                            @error('tanggal_daftar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Status --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label dropdown status --}}
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan status --}}
                            <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror">
                                {{-- Opsi 'Aktif' sebagai default --}}
                                <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                {{-- Opsi 'Nonaktif' --}}
                                <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                            {{-- Pesan error jika validasi status gagal --}}
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Garis pemisah sebelum bagian tombol aksi --}}
                    <hr>

                    {{-- Bagian tombol aksi --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol Batal untuk kembali ke halaman daftar anggota --}}
                        <a href="{{ route('anggota.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        {{-- Tombol Submit untuk menyimpan data ke database --}}
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mendorong script tambahan ke dalam stack 'scripts' --}}
@push('scripts')
{{-- Mengimpor pustaka Flatpickr (JS) dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Mengimpor file bahasa Indonesia untuk Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    // Inisialisasi Flatpickr untuk elemen dengan id 'tanggal_lahir'
    flatpickr("#tanggal_lahir", {
        dateFormat: "Y-m-d",        // Format value yang dikirim ke server (YYYY-MM-DD)
        maxDate: "today",           // Tanggal maksimal adalah hari ini (tidak bisa memilih tanggal masa depan)
        locale: "id",               // Mengatur bahasa kalender menjadi bahasa Indonesia
        altInput: true,             // Membuat input tiruan agar tampilan lebih mudah dibaca user
        altFormat: "d F Y",         // Format tampilan di layar (Contoh: 17 Agustus 1945)
    });

    // Inisialisasi Flatpickr untuk elemen dengan id 'tanggal_daftar'
    flatpickr("#tanggal_daftar", {
        dateFormat: "Y-m-d",        // Format value yang dikirim ke server
        maxDate: "today",           // Tanggal maksimal adalah hari ini
        locale: "id",               // Menggunakan bahasa Indonesia
        altInput: true,             // Menampilkan format yang mudah dibaca
        altFormat: "d F Y",         // Format tampilan (DD Bulan YYYY)
        defaultDate: "today",       // Secara default sudah terpilih hari ini
    });

    // Event listener pada input 'telepon' untuk memastikan hanya angka dan '+' yang bisa diinput
    document.getElementById('telepon').addEventListener('input', function() {
        // Menggunakan Regex untuk menghapus semua karakter selain angka (0-9) dan simbol '+'
        let value = this.value.replace(/[^\d+]/g, '');
        this.value = value;
    });
</script>
@endpush
</x-app-layout>