<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gaming CMS Forum')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <header class="border-b border-slate-800 bg-slate-900/60">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('forum.index') }}" class="text-lg font-semibold text-white">Gaming CMS</a>
                <a href="{{ route('forum.index') }}" class="text-slate-300 hover:text-white">Forum</a>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <a href="/admin" class="text-slate-300 hover:text-white">Admin</a>
                @auth
                    <span class="text-slate-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-white">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="text-slate-300 hover:text-white">Registrieren</a>
                    <span class="text-slate-500">Gast</span>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded border border-emerald-600/40 bg-emerald-950/40 px-4 py-3 text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
