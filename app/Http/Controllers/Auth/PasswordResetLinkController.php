<?php

// ============================================================
// FILE: PasswordResetLinkController.php
// FUNGSI: Mengelola pengiriman link reset password ke email user
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan view untuk meminta link reset password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirimkan link reset password ke email yang diinput.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input email
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Mengirim link reset password melalui email menggunakan facade Password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Redirect kembali dengan status sukses atau error
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
