@extends('layouts.app')

@section('title', 'Daftar Akun - Portal Aspirasi Kampus')

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
            <span class="mb-1 block text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Registrasi Mahasiswa</span>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Buat Akun Baru</h1>
            <p class="mt-1 text-xs text-zinc-400">Daftarkan akun untuk mengirim aspirasi &amp; pengaduan kampus.</p>

            <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-4">
                @csrf

                <div>
                    <label for="name" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required minlength="3" maxlength="100" autocomplete="name" autofocus
                        placeholder="Nama sesuai kartu mahasiswa"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="nim" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">NIM</label>
                    <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required minlength="3" maxlength="20"
                        placeholder="Contoh: 220101042" pattern="[A-Za-z0-9\-]+"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 font-mono text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="100" autocomplete="email"
                        placeholder="nama@student.kampus.ac.id"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Password</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                        placeholder="Min. 8 karakter (huruf besar & angka)"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-crimson-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 active:scale-[0.98]">
                    DAFTAR SEKARANG
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-zinc-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-crimson-500 transition hover:text-crimson-400 hover:underline">
                    Masuk di sini
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
