{{-- Menggunakan komponen layout utama aplikasi dengan tema bootstrap dan judul 'Edit Anggota' --}}
<x-app-layout theme="bootstrap" title="Edit Anggota">

{{-- Menambahkan stylesheet Flatpickr ke dalam stack 'styles' untuk tampilan datepicker --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

{{-- Container utama form edit, dipusatkan menggunakan grid Bootstrap --}}
<div class="row justify-content-center">
    <div class="col-md-10">
        {{-- Card sebagai pembungkus form --}}
        <div class="card">
            {{-- Header card dengan latar belakang peringatan (kuning) untuk menandakan mode edit --}}
            <div class="card-header bg-warning">
                <h4 class="mb-0">
                    {{-- Ikon pencil-square dari Bootstrap Icons --}}
                    <i class="bi bi-pencil-square"></i>
                    Edit Anggota: {{ $anggota->nama }}
                </h4>
            </div>
            
            {{-- Bagian isi (body) card yang memuat form --}}
            <div class="card-body">
                {{-- Form untuk mengupdate data anggota, mengarah ke rute 'anggota.update' dengan parameter ID anggota --}}
                <form action="{{ route('anggota.update', $anggota->id) }}" method="POST">
                    {{-- Token CSRF untuk keamanan form dari serangan Cross-Site Request Forgery --}}
                    @csrf
                    {{-- Method spoofing untuk mengubah request POST menjadi PUT sesuai standar RESTful API Laravel --}}
                    @method('PUT')

                    {{-- Baris pertama form: Kode Anggota dan Nama Lengkap --}}
                    <div class="row">
                        {{-- Kolom Input: Kode Anggota --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk input kode anggota --}}
                            <label for="kode_anggota" class="form-label">
                                Kode Anggota <span class="text-danger">*</span>
                            </label>
                            {{-- Input text untuk kode_anggota, menggunakan nilai dari database jika old() kosong --}}
                            <input type="text"
                                name="kode_anggota"
                                id="kode_anggota"
                                class="form-control @error('kode_anggota') is-invalid @enderror"
                                value="{{ old('kode_anggota', $anggota->kode_anggota) }}">
                            {{-- Pesan error validasi untuk kode_anggota --}}
                            @error('kode_anggota')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Nama Lengkap --}}
                        <div class="col-md-8 mb-3">
                            {{-- Label untuk input nama --}}
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            {{-- Input text untuk nama, menggunakan nilai dari database sebagai default --}}
                            <input type="text"
                                name="nama"
                                id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $anggota->nama) }}">
                            {{-- Pesan error validasi untuk nama --}}
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris kedua form: Email dan Nomor Telepon --}}
                    <div class="row">
                        {{-- Kolom Input: Email --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label untuk input email --}}
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            {{-- Input email, memuat nilai sebelumnya dari old() atau dari database --}}
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $anggota->email) }}">
                            {{-- Pesan error validasi untuk email --}}
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Nomor Telepon --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label untuk input telepon --}}
                            <label for="telepon" class="form-label">
                                Nomor Telepon <span class="text-danger">*</span>
                            </label>
                            {{-- Input teks untuk telepon --}}
                            <input type="text"
                                name="telepon"
                                id="telepon"
                                class="form-control @error('telepon') is-invalid @enderror"
                                value="{{ old('telepon', $anggota->telepon) }}">
                            {{-- Pesan error validasi untuk telepon --}}
                            @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris ketiga form: Alamat (memenuhi satu baris penuh) --}}
                    <div class="mb-3">
                        {{-- Label untuk input alamat --}}
                        <label for="alamat" class="form-label">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        {{-- Textarea untuk alamat, menampilkan data dari database di dalam tag --}}
                        <textarea name="alamat"
                            id="alamat"
                            rows="3"
                            class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $anggota->alamat) }}</textarea>
                        {{-- Pesan error validasi untuk alamat --}}
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Baris keempat form: Tanggal Lahir, Jenis Kelamin, dan Pekerjaan --}}
                    <div class="row">
                        {{-- Kolom Input: Tanggal Lahir --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk input tanggal lahir --}}
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            {{-- Input date, memformat instance Carbon dari database menjadi Y-m-d --}}
                            <input type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}">
                            {{-- Pesan error validasi untuk tanggal lahir --}}
                            @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Jenis Kelamin --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk dropdown jenis kelamin --}}
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan jenis kelamin --}}
                            <select name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                {{-- Melakukan looping array untuk membuat opsi secara dinamis dan mengecek apakah terpilih --}}
                                @foreach(['Laki-laki', 'Perempuan'] as $jk)
                                <option value="{{ $jk }}"
                                    {{ old('jenis_kelamin', $anggota->jenis_kelamin) == $jk ? 'selected' : '' }}>
                                    {{ $jk }}
                                </option>
                                @endforeach
                            </select>
                            {{-- Pesan error validasi untuk jenis kelamin --}}
                            @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Pekerjaan --}}
                        <div class="col-md-4 mb-3">
                            {{-- Label untuk input pekerjaan --}}
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            {{-- Input teks untuk pekerjaan --}}
                            <input type="text"
                                name="pekerjaan"
                                id="pekerjaan"
                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                value="{{ old('pekerjaan', $anggota->pekerjaan) }}">
                            {{-- Pesan error validasi untuk pekerjaan --}}
                            @error('pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris kelima form: Tanggal Daftar dan Status --}}
                    <div class="row">
                        {{-- Kolom Input: Tanggal Daftar --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label untuk input tanggal daftar --}}
                            <label for="tanggal_daftar" class="form-label">
                                Tanggal Pendaftaran <span class="text-danger">*</span>
                            </label>
                            {{-- Input date untuk tanggal daftar, memformat Carbon ke Y-m-d --}}
                            <input type="date"
                                name="tanggal_daftar"
                                id="tanggal_daftar"
                                class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                value="{{ old('tanggal_daftar', $anggota->tanggal_daftar?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}">
                            {{-- Pesan error validasi untuk tanggal daftar --}}
                            @error('tanggal_daftar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kolom Input: Status Anggota --}}
                        <div class="col-md-6 mb-3">
                            {{-- Label untuk dropdown status --}}
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            {{-- Dropdown pilihan status --}}
                            <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror">
                                {{-- Melakukan perulangan untuk status 'Aktif' dan 'Nonaktif' dan mengecek kecocokan --}}
                                @foreach(['Aktif', 'Nonaktif'] as $st)
                                <option value="{{ $st }}"
                                    {{ old('status', $anggota->status) == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                                @endforeach
                            </select>
                            {{-- Pesan error validasi untuk status --}}
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Garis pembatas sebelum area tombol --}}
                    <hr>

                    {{-- Bagian tombol navigasi dan submit --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol untuk membatalkan edit dan kembali ke detail anggota --}}
                        <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        {{-- Tombol submit untuk menyimpan perubahan ke database --}}
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Update Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card tambahan untuk menampilkan Informasi Update dari sistem --}}
        <div class="card mt-3">
            <div class="card-body">
                <small class="text-muted">
                    {{-- Menampilkan info timestamp kapan data dibuat dan diubah, serta durasi keanggotaan --}}
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong><br />
                    - Anggota terdaftar: {{ $anggota->created_at->format('d M Y H:i') }}<br />
                    - Terakhir diupdate: {{ $anggota->updated_at->format('d M Y H:i') }}<br />
                    - Lama menjadi anggota: {{ $anggota->lama_anggota }} hari ({{ round($anggota->lama_anggota / 365, 1) }} tahun)
                </small>
            </div>
        </div>
    </div>
</div>

{{-- Menambahkan script ke stack 'scripts' di akhir dokumen layout --}}
@push('scripts')
{{-- Memuat skrip JS Flatpickr untuk interaksi date picker --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Memuat paket bahasa Indonesia untuk Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    // Inisialisasi plugin Flatpickr pada elemen dengan id #tanggal_lahir
    flatpickr("#tanggal_lahir", {
        dateFormat: "Y-m-d",        // Menetapkan format nilai input (YYYY-MM-DD)
        maxDate: "today",           // Tanggal maksimal dibatasi hingga hari ini
        locale: "id",               // Bahasa tampilan (Indonesia)
        altInput: true,             // Menampilkan input alternatif yang lebih ramah pengguna
        altFormat: "d F Y",         // Format tanggal pada input alternatif (DD Bulan YYYY)
    });

    // Inisialisasi plugin Flatpickr pada elemen dengan id #tanggal_daftar
    flatpickr("#tanggal_daftar", {
        dateFormat: "Y-m-d",        // Menetapkan format nilai input
        maxDate: "today",           // Tanggal maksimal dibatasi
        locale: "id",               // Bahasa tampilan (Indonesia)
        altInput: true,             // Membuat input alternatif 
        altFormat: "d F Y",         // Format tampilan 
    });
</script>
@endpush
</x-app-layout>