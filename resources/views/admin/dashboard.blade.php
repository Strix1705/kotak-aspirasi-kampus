@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">

    {{-- Header asimetris + tombol export --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="max-w-xl">
            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Panel Admin</span>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Rekap Keluhan Aspirasi</h1>
            <p class="mt-1 text-xs text-zinc-400">Monitoring, tindak lanjut, dan ekspor data laporan mahasiswa.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.export.pdf') }}"
                class="rounded-xl bg-crimson-600 px-4 py-2.5 text-[11px] font-bold tracking-wide text-white shadow-lg shadow-crimson-950/50 transition hover:bg-crimson-500">
                EXPORT PDF BULAN INI
            </a>
            <a href="{{ route('admin.export.csv') }}"
                class="rounded-xl border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-[11px] font-bold tracking-wide text-zinc-200 transition hover:bg-zinc-700">
                EXPORT CSV
            </a>
        </div>
    </div>

    {{-- Kartu analitik --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-5">
        @php
            $cards = [
                ['label' => 'Total Laporan', 'value' => $analytics['total'], 'accent' => 'text-white', 'bar' => 'w-full bg-zinc-600'],
                ['label' => 'Menunggu', 'value' => $analytics['menunggu'], 'accent' => 'text-amber-400', 'bar' => 'w-full bg-amber-500'],
                ['label' => 'Fasilitas', 'value' => $analytics['fasilitas'], 'accent' => 'text-crimson-400', 'bar' => 'w-full bg-crimson-600'],
                ['label' => 'Akademik', 'value' => $analytics['akademik'], 'accent' => 'text-sky-400', 'bar' => 'w-full bg-sky-500'],
                ['label' => 'Layanan', 'value' => $analytics['layanan'], 'accent' => 'text-emerald-400', 'bar' => 'w-full bg-emerald-500'],
            ];
        @endphp

        @foreach ($cards as $i => $card)
            <div class="relative overflow-hidden rounded-2xl border border-zinc-800/70 bg-zinc-900/50 p-4 backdrop-blur-md transition hover:border-zinc-700 {{ $i === 0 ? 'lg:order-none lg:col-span-1' : '' }}">
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $card['label'] }}</p>
                <p class="mt-1 text-3xl font-black {{ $card['accent'] }}">{{ $card['value'] }}</p>
                <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-zinc-800">
                    <div class="h-full {{ $card['bar'] }}" style="width: {{ $analytics['total'] > 0 ? round(($card['value'] / $analytics['total']) * 100) : 0 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Daftar laporan --}}
    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <h2 class="text-lg font-extrabold tracking-tight text-white">Semua Laporan Masuk</h2>
            <span class="text-[11px] text-zinc-500">urut terbaru</span>
        </div>

        @forelse ($aspirations as $item)
            <div class="rounded-2xl border border-zinc-800/60 bg-zinc-900/40 p-5 backdrop-blur-md transition hover:border-zinc-700/70 sm:p-6">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

                    {{-- Info laporan --}}
                    <div class="lg:col-span-2">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <span class="font-mono text-[11px] text-zinc-500">{{ $item->user->nim }}</span>
                            <span class="h-1 w-1 rounded-full bg-zinc-700"></span>
                            <span class="text-[11px] font-bold text-zinc-300">{{ $item->user->name }}</span>
                            <span class="rounded-md border border-crimson-950 bg-zinc-800 px-2 py-0.5 text-[10px] font-bold text-crimson-400">{{ $item->category }}</span>

                            @php
                                $statusStyles = [
                                    'Menunggu' => 'bg-amber-950/60 text-amber-400 border-amber-800/40',
                                    'Diproses' => 'bg-sky-950/60 text-sky-400 border-sky-800/40',
                                    'Selesai' => 'bg-emerald-950/60 text-emerald-400 border-emerald-800/40',
                                    'Ditolak' => 'bg-crimson-950/60 text-crimson-400 border-crimson-800/40',
                                ];
                            @endphp
                            <span class="rounded-full border px-2 py-0.5 text-[10px] font-extrabold {{ $statusStyles[$item->status] ?? '' }}">{{ $item->status }}</span>
                        </div>

                        <h3 class="text-base font-bold text-white">{{ $item->title }}</h3>
                        <p class="mt-1.5 whitespace-pre-line text-xs leading-relaxed text-zinc-400">{{ $item->description }}</p>
                        <p class="mt-3 text-[10px] uppercase tracking-widest text-zinc-600">
                            #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }} &middot; {{ $item->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    {{-- Form update status --}}
                    <div class="space-y-3 rounded-xl border border-zinc-800/80 bg-zinc-950/60 p-4">
                        <form action="{{ route('admin.aspirations.update', $item->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Ubah Status</label>
                                <select name="status"
                                    class="w-full rounded-lg border border-zinc-700 bg-zinc-900 px-3 py-2 text-xs font-semibold text-white outline-none focus:border-crimson-600">
                                    @foreach (['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $status)
                                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tanggapan</label>
                                <textarea name="response" rows="3" maxlength="5000"
                                    placeholder="Balasan untuk mahasiswa..."
                                    class="w-full resize-y rounded-lg border border-zinc-700 bg-zinc-900 p-2.5 text-xs text-white outline-none placeholder:text-zinc-600 focus:border-crimson-600">{{ old('response', $item->response) }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full rounded-lg bg-crimson-600 py-2 text-[11px] font-bold text-white transition hover:bg-crimson-500">
                                SIMPAN STATUS &amp; TANGGAPAN
                            </button>
                        </form>

                        <form action="{{ route('admin.aspirations.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Hapus laporan ini? Tindakan tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full rounded-lg border border-zinc-700 bg-zinc-800 py-2 text-[11px] font-bold text-zinc-300 transition hover:border-crimson-800 hover:bg-crimson-950/60 hover:text-crimson-400">
                                HAPUS LAPORAN (SPAM)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-zinc-800 bg-zinc-900/20 p-10 text-center">
                <p class="text-sm font-semibold text-zinc-400">Belum ada laporan masuk.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
