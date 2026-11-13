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
                <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-700 bg-zinc-800 text-xs font-bold text-crimson-400">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-lg border border-crimson-900/60 bg-crimson-950/40 px-3 py-1.5 text-[11px] font-bold tracking-wide text-crimson-400 transition hover:bg-crimson-900/50 hover:text-crimson-300">
                        LOGOUT
                    </button>
                </form>
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
