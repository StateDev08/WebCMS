@extends('layouts.forum')

@section('title', 'Login')

@section('content')
    <div class="mx-auto max-w-md rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm uppercase tracking-widest text-emerald-400">Account</div>
        <h1 class="mt-2 text-2xl font-semibold text-white">Login</h1>

        @if ($errors->any())
            <div class="mt-4 rounded border border-rose-500/40 bg-rose-950/30 px-4 py-3 text-rose-200">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm text-slate-400">E-Mail</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-400">Passwort</label>
                <input type="password" name="password" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <button type="submit" class="w-full rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                Einloggen
            </button>
        </form>

        <p class="mt-4 text-sm text-slate-400">
            Noch kein Konto?
            <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300">Jetzt registrieren</a>
        </p>
    </div>
@endsection
