@extends('layouts.forum')

@section('title', 'Registrieren')

@section('content')
    <div class="mx-auto max-w-md rounded border border-slate-800 bg-slate-900/40 p-6">
        <h1 class="text-xl font-semibold text-white">Registrieren</h1>

        @if ($errors->any())
            <div class="mt-4 rounded border border-rose-500/40 bg-rose-950/30 px-4 py-3 text-rose-200">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm text-slate-400">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-400">E-Mail</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-400">Passwort</label>
                <input type="password" name="password" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-400">Passwort bestätigen</label>
                <input type="password" name="password_confirmation" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
            </div>
            <button type="submit" class="w-full rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                Registrieren
            </button>
        </form>

        <p class="mt-4 text-sm text-slate-400">
            Bereits registriert?
            <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300">Zum Login</a>
        </p>
    </div>
@endsection
