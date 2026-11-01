<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PasswordChangeController extends Controller
{
    /** Form ganti password wajib untuk anggota baru */
    public function edit()
    {
        return view('auth.password-change');
    }

    /** Proses ganti password */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('anggota.dashboard')
            ->with('success', 'Password berhasil diganti! Selamat datang di SAC 🏹');
    }
}
