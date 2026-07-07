<?php

// ============================================================
// FILE: PasswordController.php
// FUNGSI: Mengelola pembaruan password untuk user yang sedang login
// ============================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Memperbarui password user.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validasi input form update password
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'], // Harus cocok dgn password lama
            'password' => ['required', Password::defaults(), 'confirmed'], // Password baru & konfirmasinya
        ]);

        // Update password baru ke database (di-hash menggunakan bcrypt)
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect kembali dengan pesan sukses
        return back()->with('status', 'password-updated');
    }
}
