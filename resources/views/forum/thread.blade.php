@extends('layouts.forum')

@section('title', $thread->title)

@section('content')
    <div class="mb-8 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm text-slate-400">
            @if ($thread->game)
                <a href="{{ route('games.show', $thread->game) }}" class="hover:text-white">
                    {{ $thread->game->getTranslation('name', app()->getLocale()) }}
                </a>
            @endif
            @if ($thread->community)
                · <a href="{{ route('communities.show', $thread->community) }}" class="hover:text-white">
                    {{ $thread->community->getTranslation('name', app()->getLocale()) }}
                </a>
            @endif
            @if ($thread->category)
                · <a href="{{ route('categories.show', $thread->category) }}" class="hover:text-white">
                    {{ $thread->category->getTranslation('name', app()->getLocale()) }}
                </a>
            @endif
        </div>
        <h1 class="text-3xl font-semibold text-white">{{ $thread->title }}</h1>
        <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-400">
            <span>von {{ $thread->author?->name ?? 'Unbekannt' }}</span>
            <span>· {{ $thread->created_at->diffForHumans() }}</span>
            <span class="rounded-full border border-slate-700 px-2 py-1 text-xs text-slate-300">
                {{ $thread->posts_count }} Beiträge
            </span>
            <span class="rounded-full border border-slate-700 px-2 py-1 text-xs text-slate-400">
                {{ $thread->views_count }} Views
            </span>
            @if ($thread->is_locked)
                <span class="rounded-full border border-rose-600/50 px-2 py-1 text-xs text-rose-300">Gesperrt</span>
            @endif
            @if ($thread->is_sticky)
                <span class="rounded-full border border-amber-500/50 px-2 py-1 text-xs text-amber-300">Sticky</span>
            @endif
        </div>
        @auth
            @if ($thread->canEdit(auth()->user()))
                <div class="mt-4 flex items-center gap-3">
                    <a href="{{ route('threads.edit', $thread) }}" class="rounded border border-slate-700 px-3 py-1 text-sm text-slate-200 hover:border-slate-500">
                        Thread bearbeiten
                    </a>
                    <form method="POST" action="{{ route('threads.destroy', $thread) }}" onsubmit="return confirm('Thread wirklich löschen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded border border-rose-600/60 px-3 py-1 text-sm text-rose-200 hover:border-rose-500">
                            Thread löschen
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    <div class="space-y-4">
        @forelse ($posts as $post)
            <article class="rounded border border-slate-800/70 bg-slate-900/40 p-4">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>{{ $post->author?->name ?? 'Unbekannt' }}</span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </div>
                <div class="prose prose-invert mt-3 max-w-none">
                    {!! app(\App\Services\ContentRenderer::class)->render($post) !!}
                </div>
                @auth
                    @if ($post->canEdit(auth()->user()))
                        <div class="mt-3 flex items-center gap-3 text-xs">
                            <a href="{{ route('posts.edit', $post) }}" class="text-slate-300 hover:text-white">Bearbeiten</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Beitrag wirklich löschen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-300 hover:text-rose-200">Löschen</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </article>
        @empty
            <p class="text-sm text-slate-500">Noch keine Beiträge.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>

    <div class="mt-10 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <h2 class="mb-4 text-lg font-semibold text-white">Antwort schreiben</h2>
        @auth
            @include('forum.partials.post-form', ['thread' => $thread])
        @else
            <p class="text-sm text-slate-400">
                Bitte <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300">einloggen</a>, um zu posten.
            </p>
        @endauth
    </div>
@endsection
