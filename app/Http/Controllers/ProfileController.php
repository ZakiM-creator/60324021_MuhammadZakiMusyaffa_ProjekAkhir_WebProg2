<?php

// ============================================================
// FILE: ProfileController.php
// FUNGSI: Mengelola profil user (edit, update, delete akun)
// ROUTE: GET /profile, PATCH /profile, DELETE /profile
// ============================================================

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // Form Request khusus validasi update profil
use Illuminate\Http\RedirectResponse;       // Type hint untuk respon redirect
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;        // Facade untuk autentikasi (login/logout)
use Illuminate\Support\Facades\Redirect;    // Facade untuk redirect
use Illuminate\View\View;                   // Type hint untuk respon view

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil user yang sedang login.
     *
     * Route: GET /profile
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        // $request->user() mengambil object User yang sedang login.
        // Ini sama dengan Auth::user().
        // Data user dikirim ke view 'profile.edit' untuk ditampilkan di form.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil user (nama, email).
     *
     * Route: PATCH /profile
     *
     * @param ProfileUpdateRequest $request — sudah divalidasi oleh Form Request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // LANGKAH 1: fill() mengisi atribut model User dengan data yang sudah divalidasi.
        // $request->validated() mengembalikan array data yang lolos validasi
        // dari ProfileUpdateRequest (misal: ['name'=>'Zaki', 'email'=>'...'])
        $request->user()->fill($request->validated());

        // LANGKAH 2: Cek apakah email berubah.
        // isDirty('email') mengembalikan TRUE jika kolom email diubah tapi belum disimpan.
        // Jika email berubah, set email_verified_at = null
        // agar user harus verifikasi ulang email barunya.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // LANGKAH 3: Simpan perubahan ke database.
        // save() menjalankan query UPDATE pada tabel users.
        $request->user()->save();

        // LANGKAH 4: Redirect kembali ke halaman edit profil dengan pesan status.
        // with('status', 'profile-updated') menyimpan flash message ke session.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun user yang sedang login (logout + delete).
     *
     * Route: DELETE /profile
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // LANGKAH 1: Validasi password sebelum menghapus akun.
        // validateWithBag('userDeletion', ...) menggunakan "error bag" khusus
        // agar error validasi tidak tercampur dengan form lain di halaman yang sama.
        // 'current_password' = rule bawaan Laravel yang membandingkan input
        // dengan password hash user di database.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // LANGKAH 2: Ambil referensi object user sebelum logout.
        $user = $request->user();

        // LANGKAH 3: Logout user dari sistem.
        // Auth::logout() menghapus sesi autentikasi.
        Auth::logout();

        // LANGKAH 4: Hapus record user dari tabel `users` di database.
        $user->delete();

        // LANGKAH 5: Bersihkan session dan regenerasi CSRF token.
        // invalidate() menghapus semua data session.
        // regenerateToken() membuat token CSRF baru (keamanan).
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // LANGKAH 6: Redirect ke halaman utama (/).
        return Redirect::to('/');
    }
}
