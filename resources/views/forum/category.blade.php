@extends('layouts.forum')

@section('title', $category->getTranslation('name', app()->getLocale()))

@section('content')
    <div class="mb-8 rounded border border-slate-800/70 bg-slate-900/40 p-6">
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
        <h1 class="text-3xl font-semibold text-white">{{ $category->getTranslation('name', app()->getLocale()) }}</h1>
        <p class="mt-3 text-slate-400">{{ $category->getTranslation('description', app()->getLocale()) }}</p>
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
            <a href="{{ route('threads.show', $thread) }}" class="block rounded border border-slate-800/70 bg-slate-900/40 p-4 transition hover:border-emerald-500/60 hover:bg-slate-900/70">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    @php
                        $name = $thread->author?->name ?? 'U';
                        $initials = strtoupper(substr(preg_replace('/\s+/', '', $name), 0, 2));
                        $colors = [
                            'bg-emerald-900 text-emerald-200',
                            'bg-blue-900 text-blue-200',
                            'bg-amber-900 text-amber-200',
                            'bg-rose-900 text-rose-200',
                            'bg-purple-900 text-purple-200',
                            'bg-cyan-900 text-cyan-200',
                        ];
                        $color = $colors[abs(crc32($name)) % count($colors)];
                    @endphp
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold ring-2 ring-white/10 {{ $color }}">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="text-base font-semibold text-white">{{ $thread->title }}</div>
                                <div class="mt-1 text-xs text-slate-400">
                                    von {{ $thread->author?->name ?? 'Unbekannt' }} · {{ $thread->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="rounded-full border border-slate-700 px-2 py-1 text-slate-300">
                            {{ $thread->posts_count }} Beiträge
                        </span>
                        <span class="rounded-full border border-slate-700 px-2 py-1 text-slate-400">
                            {{ $thread->views_count }} Views
                        </span>
                        @if ($thread->is_locked)
                            <span class="rounded-full border border-rose-600/50 px-2 py-1 text-rose-300">Gesperrt</span>
                        @endif
                        @if ($thread->is_sticky)
                            <span class="rounded-full border border-amber-500/50 px-2 py-1 text-amber-300">Sticky</span>
                        @endif
                    </div>
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
