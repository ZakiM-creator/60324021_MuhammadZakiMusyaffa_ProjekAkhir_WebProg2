{{-- 
============================================================
FILE: guest.blade.php
FUNGSI: Layout template untuk halaman tamu (belum login)
============================================================
--}}
<!DOCTYPE html>
{{-- Mengambil pengaturan bahasa aplikasi dari Laravel, mengubah underscore (_) menjadi strip (-) --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Mengatur karakter encoding ke utf-8 (mendukung semua karakter universal) --}}
        <meta charset="utf-8">
        {{-- Memastikan halaman responsif pada perangkat mobile (lebar mengikuti layar) --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- Token CSRF untuk keamanan, digunakan jika ada request AJAX/form dari JS --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Menampilkan judul aplikasi yang disetel di file konfigurasi (.env / config/app.php) --}}
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- Preconnect mempercepat proses pemuatan font dari server eksternal --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Memuat font Figtree dari bunny.net (alternatif Google Fonts yang ramah privasi) --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        {{-- Direktif Vite untuk memuat file CSS dan JS utama hasil kompilasi --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- font-sans: Menggunakan font sans-serif dasar. text-gray-900: Warna teks abu gelap. antialiased: Menghaluskan rendering font di Mac/iOS --}}
    <body class="font-sans text-gray-900 antialiased">
        {{-- min-h-screen: Tinggi minimum 100vh. flex: Mengaktifkan flexbox. pt-6 sm:pt-0: Padding top. bg-gray-100: Warna latar abu-abu terang --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            {{-- Bagian pembungkus Logo Aplikasi --}}
            <div>
                {{-- Link yang mengarah ke halaman utama (home) --}}
                <a href="/">
                    {{-- Komponen logo aplikasi bawaan Laravel Breeze --}}
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            {{-- Kotak konten putih untuk form (Login, Register, dll) --}}
            {{-- max-w-md: Lebar maksimal. mt-6: Margin atas. px-6 py-4: Padding. bg-white shadow-md rounded-lg: Latar putih, bayangan, sudut melengkung --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{-- $slot adalah variabel spesial Blade Component. --}}
                {{-- Di sinilah konten dari view yang menggunakan layout ini akan dimasukkan (di-inject). --}}
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
