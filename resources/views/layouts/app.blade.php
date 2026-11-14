<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Aspirasi Kampus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased selection:bg-crimson-600 selection:text-white">

    {{-- Background gradient asimetris --}}
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-crimson-950/40 via-zinc-950 to-zinc-950"></div>
    <div class="pointer-events-none fixed -left-32 top-1/3 -z-10 h-96 w-96 rounded-full bg-crimson-700/10 blur-[120px]"></div>

    {{-- Navbar glassmorphism --}}
    <header class="sticky top-0 z-50 border-b border-zinc-800/60 bg-zinc-900/40 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
            <a href="{{ url()->current() }}" class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-crimson-600"></span>
                <span class="bg-gradient-to-r from-white via-zinc-200 to-crimson-400 bg-clip-text text-sm font-extrabold tracking-tight text-transparent sm:text-base">
                    ASPIRASI KAMPUS
                </span>
            </a>

            @auth
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden text-right sm:block">
                    <p class="text-xs font-semibold text-zinc-200">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] uppercase tracking-widest text-zinc-500">{{ Auth::user()->role }}</p>
                </div>

                {{-- Dropdown profil --}}
                <details class="relative group z-40">
                    <summary class="flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-700 bg-zinc-800 px-3 py-2 text-xs font-bold text-crimson-400 transition hover:bg-zinc-700 sm:px-4">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        <span class="hidden sm:inline text-[10px] text-zinc-400">▼</span>
                    </summary>

                    <div class="absolute right-0 mt-2 w-52 origin-top-right rounded-2xl border border-zinc-800 bg-zinc-900/95 p-2 shadow-2xl shadow-black/70 backdrop-blur-xl">
                        <div class="border-b border-zinc-800 px-3 py-2.5">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-zinc-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-zinc-300 transition hover:bg-zinc-800 hover:text-white">
                                <svg class="h-4 w-4 text-crimson-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/></svg>
                                Profil Saya
                            </a>
                        </div>

                        <div class="border-t border-zinc-800 pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-rose-400 transition hover:bg-rose-950/40 text-left">
                                    <svg class="h-4 w-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            </div>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-800/50 bg-emerald-950/50 px-4 py-3 text-sm font-medium text-emerald-400 backdrop-blur-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-crimson-800/50 bg-crimson-950/50 px-4 py-3 text-sm font-medium text-crimson-400 backdrop-blur-md">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-crimson-800/50 bg-crimson-950/50 px-4 py-3 text-sm text-crimson-300 backdrop-blur-md">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-zinc-900 py-6 text-center text-[11px] text-zinc-600">
        Portal Aspirasi &amp; Pengaduan Kampus &mdash; Secure Coding Edition
    </footer>
</body>
</html>
