@extends('layouts.forum')

@section('title', 'Neuer Thread')

@section('content')
    <div class="mb-8 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm uppercase tracking-widest text-emerald-400">Neuer Thread</div>
        <h1 class="mt-2 text-3xl font-semibold text-white">Thread erstellen</h1>
        <p class="mt-3 text-slate-400">Wähle eine Kategorie und schreibe den ersten Beitrag.</p>
    </div>

    <div class="rounded border border-slate-800/70 bg-slate-900/40 p-6">
        @include('forum.partials.post-form', [
            'action' => route('threads.store'),
            'method' => 'POST',
            'showTitle' => true,
            'showCategorySelect' => true,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ])
    </div>
@endsection
