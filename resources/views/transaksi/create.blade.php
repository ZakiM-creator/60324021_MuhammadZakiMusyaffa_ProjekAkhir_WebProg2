{{-- 
============================================================
FILE: transaksi/create.blade.php
FUNGSI: Menampilkan form untuk membuat transaksi peminjaman buku baru
============================================================
--}}
{{-- Memanggil komponen layout utama dan menetapkan judul halaman --}}
<x-app-layout theme="bootstrap" title="Transaksi Peminjaman">
<div class="row justify-content-center">
    <div class="col-md-8">
        {{-- Card penampung form --}}
        <div class="card">
            {{-- Header card --}}
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i>
                    Form Peminjaman Buku
                </h4>
            </div>
            <div class="card-body">
                {{-- Form untuk mengirim data ke route transaksi.store dengan method POST --}}
                <form action="{{ route('transaksi.store') }}" method="POST">
                    {{-- Token perlindungan CSRF (Cross-Site Request Forgery) --}}
                    @csrf

                    {{-- BAGIAN: Pilih Anggota --}}
                    <div class="mb-3">
                        <label for="anggota_id" class="form-label">
                            Pilih Anggota <span class="text-danger">*</span>
                        </label>
                        {{-- Dropdown pilihan anggota --}}
                        <select name="anggota_id"
                            id="anggota_id"
                            class="form-select @error('anggota_id') is-invalid @enderror">
                            <option value="">-- Pilih Anggota --</option>
                            {{-- Looping data anggota untuk opsi --}}
                            @foreach($anggotas as $anggota)
                            {{-- Menjaga nilai pilihan sebelumnya dengan helper old() --}}
                            <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                            </option>
                            @endforeach
                        </select>
                        {{-- Menampilkan pesan error jika validasi anggota_id gagal --}}
                        @error('anggota_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya anggota dengan status Aktif yang dapat meminjam</small>
                    </div>

                    {{-- BAGIAN: Simulasi Scanner Barcode --}}
                    <div class="mb-3 p-3 bg-light border rounded border-primary">
                        <label for="scanner_input" class="form-label fw-bold text-primary">
                            <i class="bi bi-upc-scan"></i> Scanner Barcode Buku
                        </label>
                        {{-- Input text untuk simulasi scanner, autofocus agar kursor langsung ke sini --}}
                        <input type="text" id="scanner_input" class="form-control" placeholder="Scan barcode di sini (Tekan Enter)..." autofocus>
                        <small class="text-muted">Simulasi: Ketik ID Buku (misal: 1, 2) lalu tekan Enter</small>
                    </div>

                    {{-- BAGIAN: Pilih Buku --}}
                    <div class="mb-3">
                        <label for="buku_id" class="form-label">
                            Pilih Buku <span class="text-danger">*</span>
                        </label>
                        {{-- Dropdown pilihan buku --}}
                        <select name="buku_id"
                            id="buku_id"
                            class="form-select @error('buku_id') is-invalid @enderror">
                            <option value="">-- Pilih Buku --</option>
                            {{-- Looping data buku yang tersedia (stok > 0 biasanya difilter di controller) --}}
                            @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                {{ $buku->judul }} - (Stok: {{ $buku->stok }})
                            </option>
                            @endforeach
                        </select>
                        {{-- Menampilkan pesan error jika validasi buku_id gagal --}}
                        @error('buku_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya buku dengan stok tersedia yang dapat dipinjam</small>
                    </div>

                    {{-- BAGIAN: Tanggal Pinjam --}}
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">
                            Tanggal Pinjam <span class="text-danger">*</span>
                        </label>
                        {{-- Input tanggal, default value hari ini --}}
                        <input type="date"
                            name="tanggal_pinjam"
                            id="tanggal_pinjam"
                            class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                            value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
                        {{-- Menampilkan pesan error jika validasi tanggal_pinjam gagal --}}
                        @error('tanggal_pinjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tanggal kembali otomatis 7 hari dari tanggal pinjam</small>
                    </div>

                    {{-- BAGIAN: Keterangan Tambahan --}}
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        {{-- Textarea untuk keterangan opsional --}}
                        <textarea name="keterangan"
                            id="keterangan"
                            rows="3"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        {{-- Menampilkan pesan error jika validasi keterangan gagal --}}
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kotak Informasi (Info Box) --}}
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informasi Peminjaman:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Durasi peminjaman: <strong>7 hari</strong></li>
                            <li>Denda keterlambatan: <strong>Rp 5.000/hari</strong></li>
                            <li>Stok buku akan berkurang otomatis setelah peminjaman</li>
                        </ul>
                    </div>

                    <hr>

                    {{-- Tombol Navigasi dan Submit --}}
                    <div class="d-flex justify-content-between">
                        {{-- Tombol kembali ke daftar transaksi --}}
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        {{-- Tombol submit (simpan) form --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Proses Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Push block untuk script ke stack 'scripts' di layout utama --}}
@push('scripts')
<script>
    // Event listener pada input scanner
    document.getElementById('scanner_input').addEventListener('keypress', function(e) {
        // Mengecek jika tombol yang ditekan adalah Enter
        if (e.key === 'Enter') {
            e.preventDefault(); // Mencegah submit form secara default
            
            // Membersihkan awalan 'BK-' dan nol di depan angka dari input pengguna
            let val = this.value.replace('BK-', '').replace(/^0+/, '');
            
            // Mengambil elemen dropdown buku
            let select = document.getElementById('buku_id');
            let options = select.options;
            let found = false;
            
            // Mencari opsi di dropdown yang value-nya sesuai dengan hasil scan
            for (let i = 0; i < options.length; i++) {
                if (options[i].value == val) {
                    select.selectedIndex = i; // Memilih opsi tersebut
                    found = true;
                    break;
                }
            }
            
            // Notifikasi Toast menggunakan SweetAlert
            if (found) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Buku berhasil di-scan!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Buku tidak ditemukan!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            // Mengosongkan kolom input setelah scan
            this.value = '';
        }
    });
</script>
@endpush
</x-app-layout>