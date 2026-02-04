<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gaming CMS Forum')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.15),_transparent_50%)]"></div>
    <header class="relative border-b border-slate-800/70 bg-slate-900/70 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('forum.index') }}" class="text-lg font-semibold text-white">Gaming CMS</a>
                <span class="text-slate-600">|</span>
                <a href="{{ route('forum.index') }}" class="text-slate-300 hover:text-white">Forum</a>
                <a href="{{ url('/cms') }}" class="text-slate-300 hover:text-white">CMS</a>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <a href="/admin" class="rounded border border-slate-700 px-3 py-1 text-slate-200 hover:border-slate-500">Admin</a>
                @auth
                    <span class="text-slate-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-white">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="rounded bg-emerald-600 px-3 py-1 text-white hover:bg-emerald-500">Registrieren</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="relative mx-auto max-w-6xl px-4 py-10">
        @if (session('status'))
            <div class="mb-6 rounded border border-emerald-600/40 bg-emerald-950/40 px-4 py-3 text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="relative border-t border-slate-800/70 bg-slate-900/60 py-6">
        <div class="mx-auto max-w-6xl px-4 text-xs text-slate-500">
            Gaming CMS · Forum
        </div>
    </footer>
</body>
</html>
