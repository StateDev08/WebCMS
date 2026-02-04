@extends('layouts.forum')

@section('title', $category->getTranslation('name', app()->getLocale()))

@section('content')
    <div class="mb-6">
        <div class="text-sm text-slate-400">
            @if ($category->game)
                <a href="{{ route('games.show', $category->game) }}" class="hover:text-white">
                    {{ $category->game->getTranslation('name', app()->getLocale()) }}
                </a>
            @endif
            @if ($category->community)
                · <a href="{{ route('communities.show', $category->community) }}" class="hover:text-white">
                    {{ $category->community->getTranslation('name', app()->getLocale()) }}
                </a>
            @endif
        </div>
        <h1 class="text-2xl font-semibold text-white">{{ $category->getTranslation('name', app()->getLocale()) }}</h1>
        <p class="mt-2 text-slate-400">{{ $category->getTranslation('description', app()->getLocale()) }}</p>
        @auth
            <div class="mt-4">
                <a href="{{ route('threads.create', ['category' => $category->id]) }}" class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Neuer Thread
                </a>
            </div>
        @else
            <div class="mt-4 text-sm text-slate-400">
                <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300">Login</a> zum Erstellen eines Threads.
            </div>
        @endauth
    </div>

    <div class="space-y-3">
        @forelse ($threads as $thread)
            <a href="{{ route('threads.show', $thread) }}" class="block rounded border border-slate-800 bg-slate-900/40 p-4 hover:border-slate-600">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-base font-semibold text-white">{{ $thread->title }}</div>
                        <div class="mt-1 text-xs text-slate-400">
                            von {{ $thread->author?->name ?? 'Unbekannt' }} · {{ $thread->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="text-xs text-slate-500">{{ $thread->posts_count }} Beiträge</div>
                </div>
            </a>
        @empty
            <p class="text-sm text-slate-500">Keine Threads vorhanden.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $threads->links() }}
    </div>
@endsection
