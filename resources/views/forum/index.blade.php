@extends('layouts.forum')

@section('title', 'Forum Start')

@section('content')
    <div class="grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2">
            <h1 class="mb-4 text-2xl font-semibold text-white">Spiele</h1>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($games as $game)
                    <a href="{{ route('games.show', $game) }}" class="rounded border border-slate-800 bg-slate-900/40 p-4 hover:border-slate-600">
                        <div class="text-lg font-semibold text-white">{{ $game->getTranslation('name', app()->getLocale()) }}</div>
                        <div class="mt-2 text-sm text-slate-400">
                            {{ $game->communities_count }} Communities · {{ $game->categories_count }} Kategorien
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <aside>
            <h2 class="mb-4 text-lg font-semibold text-white">Neueste Threads</h2>
            <div class="space-y-3">
                @forelse ($latestThreads as $thread)
                    <a href="{{ route('threads.show', $thread) }}" class="block rounded border border-slate-800 bg-slate-900/40 p-3 hover:border-slate-600">
                        <div class="text-sm font-semibold text-white">{{ $thread->title }}</div>
                        <div class="mt-1 text-xs text-slate-400">
                            @if ($thread->category)
                                {{ $thread->category->getTranslation('name', app()->getLocale()) }}
                            @endif
                            · {{ $thread->created_at->diffForHumans() }}
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Noch keine Threads vorhanden.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
