<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Inherit dari class Auth
use Illuminate\Notifications\Notifiable;

// Di Laravel 11+, terdapat fitur Attributes modern (PHP 8 attributes)
// Ini adalah alternatif dari protected $fillable dan protected $hidden.
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    // Tetap menuliskan array fillable sebagai cara standar mengamankan form input.
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /** @use HasFactory<UserFactory> */
    
    // Menggunakan trait HasFactory (untuk testing/dummy) dan Notifiable (untuk fitur notifikasi email)
    use HasFactory, Notifiable;

    /**
     * Konversi tipe data otomatis saat dibaca/ditulis ke DB.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // email_verified_at diubah menjadi instance waktu (Carbon datetime)
            'email_verified_at' => 'datetime',
            
            // Kolom password secara otomatis di-hash (menggunakan bcrypt/argon2) 
            // tanpa perlu fungsi Hash::make() manual (sejak Laravel 11).
            'password' => 'hashed',
        ];
    }
}
