@extends('cms.layout')

@section('title', 'Beiträge')

@section('content')
    <div class="space-y-6">
        <header class="rounded border border-slate-800/70 bg-slate-900/40 p-6">
            <div class="text-sm uppercase tracking-widest text-blue-300">News</div>
            <h1 class="mt-2 text-3xl font-semibold text-white">Beiträge</h1>
        </header>

        @forelse ($posts as $post)
            <article class="rounded border border-slate-800/70 bg-slate-900/40 p-4 transition hover:border-emerald-500/60 hover:bg-slate-900/70">
                <h2 class="text-xl font-semibold text-white">
                    <a href="{{ route('cms.posts.show', $post->slug) }}" class="hover:text-emerald-400">
                        {{ $post->title }}
                    </a>
                </h2>
                @if ($post->excerpt)
                    <p class="mt-2 text-slate-400">{{ $post->excerpt }}</p>
                @endif
            </article>
        @empty
            <p class="text-slate-400">Keine Beiträge vorhanden.</p>
        @endforelse

        <div>
            {{ $posts->links() }}
        </div>
    </div>
@endsection
