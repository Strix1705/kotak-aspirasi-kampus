@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12 lg:gap-8">

    {{-- Kolom kiri (asimetris): Form pengajuan --}}
    <div class="relative overflow-hidden rounded-3xl border border-zinc-800/70 bg-zinc-900/50 p-6 shadow-2xl shadow-black/30 backdrop-blur-xl lg:col-span-5 lg:sticky lg:top-24">
        <div class="absolute -left-16 -top-16 h-44 w-44 rounded-full bg-crimson-600/15 blur-3xl"></div>

        <div class="relative">
            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Laporan Baru</span>
            <h2 class="mt-1 text-xl font-extrabold tracking-tight text-white">Ajukan Aspirasi</h2>
            <p class="mt-1 text-xs leading-relaxed text-zinc-400">Sampaikan keluhan Anda. Data tersimpan aman dan terbatas hanya untuk akun Anda.</p>

            <form action="{{ route('mahasiswa.aspirations.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="category" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Kategori</label>
                    <select id="category" name="category" required
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                        <option value="Fasilitas" {{ old('category') === 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="Akademik" {{ old('category') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Layanan" {{ old('category') === 'Layanan' ? 'selected' : '' }}>Layanan</option>
                    </select>
                </div>

                <div>
                    <label for="title" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Judul</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="150" minlength="5"
                        placeholder="Ringkasan isu (min. 5 karakter)"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-zinc-400">Deskripsi</label>
                    <textarea id="description" name="description" rows="6" required minlength="10" maxlength="5000"
                        placeholder="Jelaskan detail pengaduan (min. 10 karakter)..."
                        class="w-full resize-y rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-sm text-zinc-100 outline-none transition placeholder:text-zinc-600 focus:border-crimson-600 focus:ring-2 focus:ring-crimson-600/30">{{ old('description') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-crimson-600 py-3.5 text-sm font-bold tracking-wide text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 active:scale-[0.98]">
                    KIRIM ASPIRASI
                </button>
            </form>
        </div>
    </div>

    {{-- Kolom kanan: Daftar laporan saya --}}
    <div class="lg:col-span-7">
        <div class="mb-5 flex items-end justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500">Riwayat</span>
                <h2 class="text-xl font-extrabold tracking-tight text-white">Laporan Saya</h2>
            </div>
            <span class="rounded-full border border-zinc-800 bg-zinc-900/60 px-3 py-1 text-[11px] font-bold text-zinc-400">
                {{ $aspirations->count() }} laporan
            </span>
        </div>

        <div class="space-y-4">
            @forelse ($aspirations as $item)
                <article class="group rounded-2xl border border-zinc-800/60 bg-zinc-900/30 p-5 backdrop-blur-md transition hover:border-zinc-700/70 hover:bg-zinc-900/50">
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                        <span class="rounded-md border border-crimson-950 bg-zinc-800 px-2.5 py-1 text-[11px] font-bold text-crimson-400">
                            {{ $item->category }}
                        </span>

                        @php
                            $statusStyles = [
                                'Menunggu' => 'bg-amber-950/60 text-amber-400 border-amber-800/40',
                                'Diproses' => 'bg-sky-950/60 text-sky-400 border-sky-800/40',
                                'Selesai' => 'bg-emerald-950/60 text-emerald-400 border-emerald-800/40',
                                'Ditolak' => 'bg-crimson-950/60 text-crimson-400 border-crimson-800/40',
                            ];
                        @endphp
                        <span class="rounded-full border px-2.5 py-1 text-[11px] font-extrabold {{ $statusStyles[$item->status] ?? '' }}">
                            {{ $item->status }}
                        </span>
                    </div>

                    {{-- Escape XSS: {{ }} = htmlspecialchars() otomatis --}}
                    <h3 class="text-base font-bold text-white">{{ $item->title }}</h3>
                    <p class="mt-1.5 whitespace-pre-line text-xs leading-relaxed text-zinc-400">{{ $item->description }}</p>

                    @if ($item->response)
                        <div class="mt-4 rounded-r-xl border-l-2 border-crimson-600 bg-zinc-950/70 p-3">
                            <span class="mb-1 block text-[10px] font-extrabold uppercase tracking-widest text-crimson-500">Tanggapan Admin</span>
                            <p class="whitespace-pre-line text-xs leading-relaxed text-zinc-300">{{ $item->response }}</p>
                        </div>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-zinc-800/50 pt-3">
                        <span class="text-[11px] text-zinc-600">
                            #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }} &middot; {{ $item->created_at->format('d M Y H:i') }}
                        </span>

                        <div class="flex gap-2">
                            <a href="{{ route('mahasiswa.aspirations.pdf', $item->id) }}"
                                class="rounded-lg border border-zinc-700 bg-zinc-800 px-3 py-1.5 text-[11px] font-bold text-zinc-200 transition hover:bg-zinc-700">
                                Cetak Tiket PDF
                            </a>

                            @if ($item->isMenunggu())
                                <form action="{{ route('mahasiswa.aspirations.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Batalkan laporan ini? Tindakan tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg border border-crimson-800/50 bg-crimson-950/50 px-3 py-1.5 text-[11px] font-bold text-crimson-400 transition hover:bg-crimson-900/60">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-zinc-800 bg-zinc-900/20 p-10 text-center">
                    <p class="text-sm font-semibold text-zinc-400">Belum ada laporan</p>
                    <p class="mt-1 text-xs text-zinc-600">Mulai kirim aspirasi Anda lewat form di samping.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
