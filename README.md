# Sistem Perpustakaan Laravel
 
## Deskripsi
Aplikasi manajemen perpustakaan berbasis web yang dibangun untuk memudahkan proses administrasi, pengelolaan buku, data anggota, serta memantau sirkulasi peminjaman dan pengembalian buku secara digital dan terstruktur.
 
## Fitur
*(Catatan: Silakan ganti teks placeholder di bawah ini dengan gambar screenshot dari aplikasi yang berjalan)*

- **Authentication**
  ![Screenshot Authentication](isi_dengan_path_atau_link_gambar_login_disini)

- **CRUD Buku & Anggota**
  ![Screenshot CRUD Buku](isi_dengan_path_atau_link_gambar_buku_disini)
  ![Screenshot CRUD Anggota](isi_dengan_path_atau_link_gambar_anggota_disini)

- **Transaksi Peminjaman & Pengembalian**
  ![Screenshot Transaksi](isi_dengan_path_atau_link_gambar_transaksi_disini)

- **Dashboard & Reports**
  ![Screenshot Dashboard](isi_dengan_path_atau_link_gambar_dashboard_disini)
  ![Screenshot Reports](isi_dengan_path_atau_link_gambar_laporan_disini)
 
## Tech Stack
- Laravel 12.x
- MySQL 8.x
- Bootstrap 5.3
 
## Instalasi
Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal (Localhost):

1. **Clone repo**
   ```bash
   git clone <url_repository_anda>
   cd Pertemuan15
   ```
2. **Install dependensi PHP (Composer)**
   ```bash
   composer install
   ```
3. **Install dependensi Frontend (NPM)**
   ```bash
   npm install
   npm run build
   ```
4. **Siapkan Environment Variables**
   ```bash
   cp .env.example .env
   ```
   *(Penting: Buka file `.env` dan pastikan konfigurasi database sudah benar, misalnya `DB_DATABASE=perpustakaan_laravel`)*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```
6. **Migrasi Database dan Seeding**
   ```bash
   php artisan migrate --seed
   ```
   *(Perintah ini akan membuat tabel di database MySQL sekaligus mengisi data awal/dummy untuk testing)*

7. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   *(Aplikasi sekarang dapat diakses melalui browser di alamat http://127.0.0.1:8000)*
