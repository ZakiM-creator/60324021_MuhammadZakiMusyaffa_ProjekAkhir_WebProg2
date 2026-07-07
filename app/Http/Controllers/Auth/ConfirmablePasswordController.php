<?php

// ============================================================
// FILE: ConfirmablePasswordController.php
// FUNGSI: Menangani konfirmasi password ulang untuk area sensitif
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Menampilkan form konfirmasi password.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Memverifikasi input password dengan password user saat ini.
     */
    public function store(Request $request): RedirectResponse
    {
        // Membandingkan input password dengan password milik user di database
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // Jika tidak cocok, throw ValidationException
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Simpan waktu konfirmasi di session agar tidak ditanya lagi sementara waktu
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirect ke halaman yang diinginkan semula
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
