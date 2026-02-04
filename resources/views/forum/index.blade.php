@extends('layouts.forum')

@section('title', 'Forum Start')

@section('content')
    <section class="mb-10 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm uppercase tracking-widest text-emerald-400">Community</div>
        <h1 class="mt-2 text-3xl font-semibold text-white">Gaming CMS Forum</h1>
        <p class="mt-3 text-slate-400">Finde Communities, starte Threads und bleibe mit deiner Community verbunden.</p>
    </section>

    <div class="grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2">
            <h2 class="mb-4 text-xl font-semibold text-white">Spiele</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($games as $game)
                    <a href="{{ route('games.show', $game) }}" class="rounded border border-slate-800/70 bg-slate-900/40 p-4 transition hover:border-emerald-500/60 hover:bg-slate-900/70">
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
                    <a href="{{ route('threads.show', $thread) }}" class="block rounded border border-slate-800/70 bg-slate-900/40 p-3 transition hover:border-emerald-500/60 hover:bg-slate-900/70">
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
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold ring-2 ring-white/10 {{ $color }}">
                                {{ $initials }}
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-white">{{ $thread->title }}</div>
                        <div class="mt-1 text-xs text-slate-400">
                            @if ($thread->category)
                                {{ $thread->category->getTranslation('name', app()->getLocale()) }}
                            @endif
                            · {{ $thread->created_at->diffForHumans() }}
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2 text-[11px] text-slate-400">
                            <span class="rounded-full border border-slate-700 px-2 py-1">{{ $thread->posts_count }} Posts</span>
                            <span class="rounded-full border border-slate-700 px-2 py-1">{{ $thread->views_count }} Views</span>
                        </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Noch keine Threads vorhanden.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
