<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit()
    {
        // Sumber data selalu user yang login, bukan ID dari request (anti-IDOR)
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        // Update HANYA milik user login. Tidak ada input user_id di form.
        Auth::user()->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Nama profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        // Verifikasi password lama dengan Hash::check
        if (!Hash::check((string) $request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        // Hash ulang dengan bcrypt sebelum disimpan
        $user->update([
            'password' => Hash::make((string) $request->validated('password')),
        ]);

        // Regenerasi session untuk mencegah session fixation
        $request->session()->regenerate();

        return redirect()->route('profile.edit')
            ->with('success', 'Kata sandi berhasil diubah.');
    }
}
