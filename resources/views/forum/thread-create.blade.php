@extends('layouts.forum')

@section('title', 'Neuer Thread')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Neuen Thread erstellen</h1>
        <p class="mt-2 text-slate-400">Wähle eine Kategorie und schreibe den ersten Beitrag.</p>
    </div>

    @include('forum.partials.post-form', [
        'action' => route('threads.store'),
        'method' => 'POST',
        'showTitle' => true,
        'showCategorySelect' => true,
        'categories' => $categories,
        'selectedCategory' => $selectedCategory,
    ])
@endsection
