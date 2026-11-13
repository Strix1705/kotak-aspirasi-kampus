<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // Eloquent + validated input: aman dari SQL injection.
        // Password otomatis di-hash bcrypt via cast 'hashed' di model User.
        $user = User::create([
            'name' => $request->validated('name'),
            'nim' => $request->validated('nim'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => 'mahasiswa', // role selalu mahasiswa, tidak boleh dipilih user
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, '.$user->name.'!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limit ditangani middleware throttle:5,1 di routes/web.php
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    }
}
