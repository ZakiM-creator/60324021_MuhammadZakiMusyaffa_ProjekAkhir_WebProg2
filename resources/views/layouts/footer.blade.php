{{-- 
============================================================
FILE: footer.blade.php
FUNGSI: Potongan layout (partial) untuk bagian footer bawah halaman
============================================================
--}}
{{-- footer: Elemen semantik HTML untuk footer. bg-light: Latar belakang warna terang. border-top: Garis batas di bagian atas --}}
<footer class="bg-light border-top">
    {{-- container: Membungkus isi footer agar lebarnya tidak penuh (punya margin kiri-kanan) --}}
    <div class="container">
        {{-- row: Membuat flexbox baris (Grid system Bootstrap). py-4: Padding atas dan bawah sebesar 4 --}}
        <div class="row py-4">
            
            {{-- Kolom Pertama: Informasi Singkat Aplikasi (lebar 6/12 kolom pada layar medium ke atas) --}}
            <div class="col-md-6">
                {{-- Judul dengan icon buku --}}
                <h5><i class="bi bi-book-fill text-primary"></i> Sistem Perpustakaan</h5>
                {{-- Deskripsi teks abu-abu (text-muted) tanpa margin bawah (mb-0) --}}
                <p class="text-muted mb-0">
                    Sistem Manajemen Perpustakaan menggunakan Laravel 12
                </p>
            </div>
            
            {{-- Kolom Kedua: Link Navigasi (lebar 3/12 kolom) --}}
            <div class="col-md-3">
                <h6>Menu</h6>
                {{-- list-unstyled: Menghilangkan bullet pada list --}}
                <ul class="list-unstyled">
                    {{-- Link ke halaman Home (url utama) --}}
                    <li><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                    {{-- Link ke daftar Buku menggunakan nama route --}}
                    <li><a href="{{ route('buku.index') }}" class="text-decoration-none">Buku</a></li>
                    {{-- Link ke daftar Anggota menggunakan nama route --}}
                    <li><a href="{{ route('anggota.index') }}" class="text-decoration-none">Anggota</a></li>
                </ul>
            </div>
            
            {{-- Kolom Ketiga: Informasi Kontak (lebar 3/12 kolom) --}}
            <div class="col-md-3">
                <h6>Kontak</h6>
                {{-- text-muted: Abu-abu. small: Ukuran teks kecil. mb-0: Hilangkan margin bawah --}}
                <p class="text-muted small mb-0">
                    {{-- Baris Email dengan icon amplop --}}
                    <i class="bi bi-envelope"></i> perpustakaan@example.com<br />
                    {{-- Baris Telepon dengan icon telepon --}}
                    <i class="bi bi-telephone"></i> (021) 1234-5678
                </p>
            </div>
        </div>
        
        {{-- Baris Terakhir (Bottom Bar) untuk Hak Cipta (Copyright) --}}
        {{-- border-top: Garis pembatas. pt-3: Padding atas sebesar 3 --}}
        <div class="row border-top pt-3">
            {{-- col: Mengambil seluruh sisa lebar kolom. text-center: Rata tengah --}}
            <div class="col text-center text-muted small">
                <p class="mb-0">
                    {{-- Fungsi date('Y') dari PHP digunakan untuk menampilkan tahun saat ini secara dinamis --}}
                    &copy; {{ date('Y') }} Sistem Perpustakaan.
                    {{-- Icon hati berwarna merah (text-danger) --}}
                    Built with <i class="bi bi-heart-fill text-danger"></i> using Laravel 12.
                </p>
            </div>
        </div>
    </div>
</footer>