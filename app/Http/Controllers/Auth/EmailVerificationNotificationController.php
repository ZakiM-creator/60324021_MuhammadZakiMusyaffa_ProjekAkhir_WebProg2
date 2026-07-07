<?php

// ============================================================
// FILE: EmailVerificationNotificationController.php
// FUNGSI: Mengirim ulang notifikasi email verifikasi
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Mengirim link verifikasi email yang baru (kirim ulang).
     */
    public function store(Request $request): RedirectResponse
    {
        // Cek apakah email user sudah diverifikasi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Kirim email verifikasi
        $request->user()->sendEmailVerificationNotification();

        // Kembali dengan pesan status sukses
        return back()->with('status', 'verification-link-sent');
    }
}
