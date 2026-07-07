<?php

// ============================================================
// FILE: AuthenticatedSessionController.php
// FUNGSI: Mengelola sesi autentikasi (Login & Logout)
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan form login.
     * Route: GET /login
     */
    public function create(): View
    {
        // Menampilkan view 'auth.login'
        return view('auth.login');
    }

    /**
     * Menangani request autentikasi (proses login).
     * Route: POST /login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Melakukan autentikasi menggunakan form request khusus LoginRequest
        // Jika gagal, otomatis redirect kembali dengan pesan error
        $request->authenticate();

        // Meregenerasi ID session untuk mencegah serangan session fixation
        $request->session()->regenerate();

        // Redirect user ke halaman yang dituju sebelum login, atau ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Menghapus sesi autentikasi (proses logout).
     * Route: POST /logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout user dari guard 'web'
        Auth::guard('web')->logout();

        // Invalidasi session saat ini
        $request->session()->invalidate();

        // Regenerasi CSRF token untuk keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/');
    }
}
