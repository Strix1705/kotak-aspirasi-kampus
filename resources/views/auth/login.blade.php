@extends('layouts.app')

@section('title', 'Login - Portal Aspirasi Kampus')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-zinc-800/70 bg-zinc-900/50 p-8 shadow-2xl shadow-black/40 backdrop-blur-xl">
        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-crimson-600/15 blur-3xl"></div>

        <a href="{{ route('home') }}"
            class="relative mb-6 inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-zinc-500 transition hover:text-crimson-400">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6"/></svg>
            Kembali ke Beranda
        </a>

        <div class="relative">
            <span class="mb-1 block text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Secure Login</span>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Selamat Datang Kembali</h1>
            <p class="mt-1 text-xs text-zinc-400">Masuk menggunakan akun kampus Anda.</p>

            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <label class="flex cursor-pointer items-center gap-2 text-xs text-zinc-400">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-zinc-700 bg-zinc-900 accent-crimson-600">
                    Ingat saya
                </label>

                <button type="submit"
                    class="w-full rounded-xl bg-crimson-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 active:scale-[0.98]">
                    MASUK
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-zinc-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-crimson-500 transition hover:text-crimson-400 hover:underline">
                    Daftar di sini
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
