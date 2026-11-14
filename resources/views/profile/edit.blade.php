@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('mahasiswa.dashboard') }}"
            class="mb-4 inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-zinc-500 transition hover:text-crimson-400">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6"/></svg>
            Kembali ke Dashboard
        </a>
        <span class="block text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Akun Saya</span>
        <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Profil Pengguna</h1>
        <p class="mt-1 text-xs text-zinc-400">Kelola identitas akun dan kata sandi Anda.</p>
    </div>

    {{-- Card 1: Ubah Profil --}}
    <section class="relative overflow-hidden rounded-3xl border border-zinc-800/70 bg-zinc-900/50 p-6 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-8">
        <div class="absolute -left-16 -top-16 h-44 w-44 rounded-full bg-crimson-600/15 blur-3xl"></div>

        <div class="relative">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-crimson-950 text-crimson-400 ring-1 ring-crimson-900/60">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                </span>
                <div>
                    <h2 class="text-base font-bold text-white">Ubah Profil</h2>
                    <p class="text-[11px] text-zinc-500">Perbarui nama tampilan akun Anda.</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required minlength="3" maxlength="100" autocomplete="name"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Email</label>
                    <input id="email" type="email" value="{{ $user->email }}" readonly disabled
                        class="w-full cursor-not-allowed rounded-xl border border-zinc-800/60 bg-zinc-950/50 px-4 py-3 text-sm text-zinc-500 outline-none">
                    <p class="mt-1.5 text-[10px] text-zinc-600">Email tidak dapat diubah. Hubungi admin bila perlu perubahan.</p>
                </div>

                <div class="flex items-center gap-2 text-[11px] text-zinc-500">
                    <span class="rounded-md border border-zinc-800 bg-zinc-950/60 px-2 py-1 font-mono">NIM: {{ $user->nim }}</span>
                    <span class="rounded-md border border-crimson-950 bg-crimson-950/40 px-2 py-1 font-bold uppercase text-crimson-400">{{ $user->role }}</span>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-crimson-600 py-3 text-sm font-bold text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 active:scale-[0.98] sm:w-auto sm:px-8">
                    SIMPAN PERUBAHAN
                </button>
            </form>
        </div>
    </section>

    {{-- Card 2: Ubah Kata Sandi --}}
    <section class="relative overflow-hidden rounded-3xl border border-zinc-800/70 bg-zinc-900/50 p-6 shadow-2xl shadow-black/30 backdrop-blur-xl sm:p-8">
        <div class="absolute -right-16 -bottom-16 h-44 w-44 rounded-full bg-rose-900/20 blur-3xl"></div>

        <div class="relative">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-950 text-rose-400 ring-1 ring-rose-900/60">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3"/></svg>
                </span>
                <div>
                    <h2 class="text-base font-bold text-white">Ubah Kata Sandi</h2>
                    <p class="text-[11px] text-zinc-500">Wajib masukkan password lama untuk verifikasi.</p>
                </div>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Password Lama</label>
                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                        placeholder="Password saat ini"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                    @error('current_password')
                        <p class="mt-1.5 text-[11px] font-medium text-crimson-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Password Baru</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                        placeholder="Min. 8 karakter (huruf besar & angka)"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                    @error('password')
                        <p class="mt-1.5 text-[11px] font-medium text-crimson-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                    @error('password_confirmation')
                        <p class="mt-1.5 text-[11px] font-medium text-crimson-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-crimson-600 py-3 text-sm font-bold text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 active:scale-[0.98] sm:w-auto sm:px-8">
                    UBAH KATA SANDI
                </button>
            </form>
        </div>
    </section>
</div>
@endsection
