<?php

// ============================================================
// FILE: EmailVerificationPromptController.php
// FUNGSI: Menampilkan halaman yang meminta user untuk verifikasi email
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Menampilkan prompt (peringatan) verifikasi email.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // Jika sudah diverifikasi, langsung redirect ke dashboard
        // Jika belum, tampilkan halaman prompt verifikasi ('auth.verify-email')
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
