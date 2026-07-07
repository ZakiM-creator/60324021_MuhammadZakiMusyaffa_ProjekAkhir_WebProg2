<?php

// ============================================================
// FILE: Controller.php
// FUNGSI: Base Controller (Induk Semua Controller)
// ============================================================
// File ini adalah "pondasi" dari seluruh controller di aplikasi.
// Setiap controller lain (BukuController, AnggotaController, dll)
// akan mewarisi (extends) class ini.
// ============================================================

namespace App\Http\Controllers;

// Mendefinisikan class Controller sebagai "abstract".
// Abstract artinya class ini TIDAK BISA di-instansiasi langsung
// (new Controller() = ERROR). Ia hanya berfungsi sebagai
// induk/parent yang diwarisi oleh semua controller lain.
abstract class Controller
{
    // Kosong. Tidak ada method/property bawaan.
    // Di Laravel versi lama, class ini berisi middleware helper, dll.
    // Di Laravel 11+, ia sudah dibuat minimal (clean).
}
