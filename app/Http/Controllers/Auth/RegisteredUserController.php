<?php

// ============================================================
// FILE: RegisteredUserController.php
// FUNGSI: Menangani pendaftaran (registrasi) user baru
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan view form registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses data registrasi dan menyimpan user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input registrasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Membuat data user baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash
        ]);

        // Trigger event Registered (biasanya untuk mengirim email verifikasi)
        event(new Registered($user));

        // Login user yang baru saja mendaftar
        Auth::login($user);

        // Redirect ke dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
