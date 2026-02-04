@extends('layouts.forum')

@section('title', $game->getTranslation('name', app()->getLocale()))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">{{ $game->getTranslation('name', app()->getLocale()) }}</h1>
        <p class="mt-2 text-slate-400">{{ $game->getTranslation('description', app()->getLocale()) }}</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">
            <div>
                <h2 class="mb-3 text-lg font-semibold text-white">Kategorien</h2>
                <div class="space-y-3">
                    @forelse ($categories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="block rounded border border-slate-800 bg-slate-900/40 p-4 hover:border-slate-600">
                            <div class="text-base font-semibold text-white">{{ $category->getTranslation('name', app()->getLocale()) }}</div>
                            <div class="mt-1 text-sm text-slate-400">{{ $category->getTranslation('description', app()->getLocale()) }}</div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Keine öffentlichen Kategorien vorhanden.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="mb-3 text-lg font-semibold text-white">Neueste Threads</h2>
                <div class="space-y-3">
                    @forelse ($latestThreads as $thread)
                        <a href="{{ route('threads.show', $thread) }}" class="block rounded border border-slate-800 bg-slate-900/40 p-3 hover:border-slate-600">
                            <div class="text-sm font-semibold text-white">{{ $thread->title }}</div>
                            <div class="mt-1 text-xs text-slate-400">
                                {{ $thread->created_at->diffForHumans() }}
                                @if ($thread->category)
                                    · {{ $thread->category->getTranslation('name', app()->getLocale()) }}
                                @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Noch keine Threads.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <aside>
            <h2 class="mb-3 text-lg font-semibold text-white">Communities</h2>
            <div class="space-y-3">
                @forelse ($communities as $community)
                    <a href="{{ route('communities.show', $community) }}" class="block rounded border border-slate-800 bg-slate-900/40 p-3 hover:border-slate-600">
                        <div class="text-sm font-semibold text-white">{{ $community->getTranslation('name', app()->getLocale()) }}</div>
                        <div class="mt-1 text-xs text-slate-400">{{ $community->getTranslation('description', app()->getLocale()) }}</div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Keine Communities vorhanden.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
