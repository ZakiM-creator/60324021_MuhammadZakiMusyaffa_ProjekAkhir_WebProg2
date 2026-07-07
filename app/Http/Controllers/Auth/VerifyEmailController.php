<?php

// ============================================================
// FILE: VerifyEmailController.php
// FUNGSI: Menangani proses verifikasi email (saat link di email diklik)
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Menandai email user sebagai 'terverifikasi'.
     * Dipanggil saat user mengklik link verifikasi di email mereka.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Jika sudah terverifikasi sebelumnya, langsung redirect ke dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // Jika belum, tandai email sebagai terverifikasi (simpan timestamp di database)
        if ($request->user()->markEmailAsVerified()) {
            // Trigger event Verified
            event(new Verified($request->user()));
        }

        // Redirect ke dashboard dengan parameter '?verified=1'
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
