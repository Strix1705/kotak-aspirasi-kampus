<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AspirasiKampus. — Portal Aspirasi & Pengaduan</title>
    <meta name="description" content="Portal aman untuk aspirasi dan pengaduan mahasiswa.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-zinc-950 text-zinc-100 antialiased selection:bg-crimson-600 selection:text-white">
    {{-- Ambient background --}}
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(ellipse_at_85%_0%,_var(--tw-gradient-stops))] from-crimson-950/50 via-zinc-950 to-zinc-950"></div>
    <div class="pointer-events-none fixed -left-44 top-1/3 -z-10 h-[32rem] w-[32rem] rounded-full bg-crimson-700/10 blur-[130px]"></div>

    <header class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 sm:pt-6">
        <nav class="flex items-center justify-between rounded-2xl border border-zinc-800/70 bg-zinc-900/40 px-4 py-3 backdrop-blur-xl sm:px-5">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="AspirasiKampus Beranda">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-crimson-600 shadow-lg shadow-crimson-950/70">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M7 8h10M7 12h6m-8 8 3.5-3H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                </span>
                <span class="text-sm font-extrabold tracking-tight text-white sm:text-base">Aspirasi<span class="text-crimson-500">Kampus.</span></span>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="rounded-lg border border-zinc-700 bg-zinc-900/60 px-3 py-2 text-[11px] font-bold text-zinc-200 transition hover:border-zinc-500 hover:bg-zinc-800 sm:px-4 sm:text-xs">MASUK</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-crimson-600 px-3 py-2 text-[11px] font-bold text-white shadow-lg shadow-crimson-950/60 transition hover:bg-crimson-500 sm:px-4 sm:text-xs">DAFTAR</a>
            </div>
        </nav>
    </header>

    <main>
        {{-- Hero --}}
        <section class="mx-auto grid max-w-7xl items-center gap-12 px-4 pb-20 pt-16 sm:px-6 sm:pb-28 sm:pt-24 lg:grid-cols-12 lg:gap-8 lg:pt-32">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 rounded-full border border-crimson-900/70 bg-crimson-950/40 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em] text-crimson-400 backdrop-blur-md">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-crimson-500"></span>
                    Ruang suara mahasiswa
                </div>

                <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Suarakan aspirasimu untuk kampus yang <span class="bg-gradient-to-r from-crimson-400 to-rose-300 bg-clip-text text-transparent">lebih baik.</span>
                </h1>
                <p class="mt-6 max-w-xl text-sm leading-7 text-zinc-400 sm:text-base">
                    Sampaikan keluhan, ide, atau masukanmu secara aman. Pantau proses laporan secara transparan hingga selesai ditindaklanjuti.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-2 rounded-xl bg-crimson-600 px-6 py-4 text-sm font-bold text-white shadow-xl shadow-crimson-950/60 transition hover:bg-crimson-500 hover:shadow-crimson-900/40">
                        Buat Laporan Sekarang
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="#alur" class="inline-flex items-center justify-center rounded-xl border border-zinc-800 bg-zinc-900/50 px-6 py-4 text-sm font-bold text-zinc-300 backdrop-blur-md transition hover:border-zinc-700 hover:bg-zinc-900">
                        Lihat Alur Pengaduan
                    </a>
                </div>

                <div class="mt-10 flex items-center gap-6 text-xs text-zinc-500">
                    <span class="flex items-center gap-2"><svg class="h-4 w-4 text-crimson-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>Aman &amp; terlindungi</span>
                    <span class="flex items-center gap-2"><svg class="h-4 w-4 text-crimson-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Status real-time</span>
                </div>
            </div>

            {{-- Hero visual asymmetric --}}
            <div class="relative mx-auto w-full max-w-md lg:col-span-5 lg:max-w-none">
                <div class="absolute -right-4 -top-6 h-40 w-40 rounded-full bg-crimson-600/20 blur-3xl"></div>
                <div class="relative rotate-[-2deg] rounded-3xl border border-zinc-700/60 bg-zinc-900/50 p-5 shadow-2xl shadow-black/50 backdrop-blur-xl transition duration-500 hover:rotate-0 sm:p-6">
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                        <div class="flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-crimson-950 text-crimson-400"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 4h.01M5.5 20h13a2 2 0 0 0 1.7-3L13.7 5a2 2 0 0 0-3.4 0L3.8 17a2 2 0 0 0 1.7 3Z"/></svg></span><div><p class="text-xs font-bold text-zinc-100">Fasilitas Kampus</p><p class="text-[10px] text-zinc-500">#ASP-00042</p></div></div>
                            <span class="rounded-full border border-sky-800/50 bg-sky-950/60 px-2.5 py-1 text-[10px] font-bold text-sky-400">Diproses</span>
                    </div>
                    <h2 class="mt-5 text-base font-bold text-white">Penerangan area parkir perlu diperbaiki</h2>
                    <p class="mt-2 text-xs leading-5 text-zinc-400">Lampu di area parkir gedung timur mati sejak minggu lalu. Mohon pengecekan untuk keamanan mahasiswa...</p>
                    <div class="mt-5 rounded-xl border-l-2 border-crimson-600 bg-zinc-950/70 p-3"><p class="text-[10px] font-bold uppercase tracking-widest text-crimson-400">Tanggapan Admin</p><p class="mt-1 text-[11px] leading-5 text-zinc-400">Tim sarana telah menerima laporan dan menjadwalkan perbaikan.</p></div>
                </div>
                <div class="absolute -bottom-5 -left-4 rounded-2xl border border-zinc-700/70 bg-zinc-900/80 px-4 py-3 shadow-xl shadow-black/40 backdrop-blur-xl sm:-left-8"><p class="text-[10px] uppercase tracking-widest text-zinc-500">Laporan tertangani</p><p class="mt-0.5 text-xl font-black text-emerald-400">98<span class="text-sm">%</span></p></div>
            </div>
        </section>

        {{-- Alur --}}
        <section id="alur" class="border-y border-zinc-900 bg-zinc-900/20 py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="max-w-xl">
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-crimson-500">Mudah &amp; Transparan</span>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Alur pengaduan tanpa rumit.</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-400">Tiga langkah sederhana dari suara Anda sampai perubahan nyata.</p>
                </div>

                <div class="mt-10 grid gap-4 md:grid-cols-3">
                    @php
                        $steps = [
                            ['number' => '01', 'title' => 'Tulis Laporan', 'text' => 'Pilih kategori, jelaskan masalah, lalu kirim laporan dengan aman.', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8m-8 4h5"/>'],
                            ['number' => '02', 'title' => 'Diproses Admin', 'text' => 'Admin kampus meninjau laporan dan memberi tanggapan secara transparan.', 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="m16 11 2 2 4-4"/>'],
                            ['number' => '03', 'title' => 'Selesai', 'text' => 'Pantau status sampai laporan selesai ditindaklanjuti oleh pihak kampus.', 'icon' => '<circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/>'],
                        ];
                    @endphp
                    @foreach ($steps as $step)
                        <article class="group relative overflow-hidden rounded-2xl border border-zinc-800/70 bg-zinc-900/50 p-6 backdrop-blur-md transition hover:-translate-y-1 hover:border-crimson-900/70">
                            <span class="absolute right-5 top-4 text-5xl font-black text-zinc-800/50 transition group-hover:text-crimson-950/60">{{ $step['number'] }}</span>
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-crimson-900/60 bg-crimson-950/50 text-crimson-400"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">{!! $step['icon'] !!}</svg></span>
                            <h3 class="mt-6 text-base font-bold text-white">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-xs leading-6 text-zinc-400">{{ $step['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Bottom CTA --}}
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24">
            <div class="relative overflow-hidden rounded-3xl border border-crimson-900/40 bg-crimson-950/30 px-6 py-12 text-center backdrop-blur-xl sm:px-12">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-crimson-600/20 blur-3xl"></div>
                <div class="relative"><h2 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Punya aspirasi untuk kampus?</h2><p class="mx-auto mt-3 max-w-lg text-sm leading-6 text-zinc-400">Jangan diam. Sampaikan suara Anda dan bantu wujudkan lingkungan kampus lebih baik.</p><a href="{{ route('register') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-crimson-600 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-crimson-950/60 transition hover:bg-crimson-500">Daftar &amp; Buat Laporan<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></a></div>
            </div>
        </section>
    </main>

    <footer class="border-t border-zinc-900 px-4 py-7 text-center text-[11px] text-zinc-600">&copy; {{ now()->year }} AspirasiKampus. Semua hak dilindungi.</footer>
</body>
</html>
