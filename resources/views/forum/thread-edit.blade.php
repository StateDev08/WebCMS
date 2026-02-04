@extends('layouts.forum')

@section('title', 'Thread bearbeiten')

@section('content')
    <div class="mb-8 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm uppercase tracking-widest text-emerald-400">Thread</div>
        <h1 class="mt-2 text-3xl font-semibold text-white">Thread bearbeiten</h1>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded border border-rose-500/40 bg-rose-950/30 px-4 py-3 text-rose-200">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('threads.update', $thread) }}" class="max-w-2xl space-y-4 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm text-slate-400">Titel</label>
            <input type="text" name="title" value="{{ old('title', $thread->title) }}" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                Speichern
            </button>
            <a href="{{ route('threads.show', $thread) }}" class="text-sm text-slate-400 hover:text-white">Abbrechen</a>
        </div>
    </form>
@endsection
