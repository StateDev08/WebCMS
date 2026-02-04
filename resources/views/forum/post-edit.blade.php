@extends('layouts.forum')

@section('title', 'Beitrag bearbeiten')

@section('content')
    <div class="mb-8 rounded border border-slate-800/70 bg-slate-900/40 p-6">
        <div class="text-sm uppercase tracking-widest text-emerald-400">Beitrag</div>
        <h1 class="mt-2 text-3xl font-semibold text-white">Beitrag bearbeiten</h1>
    </div>

    <div class="rounded border border-slate-800/70 bg-slate-900/40 p-6">
        @include('forum.partials.post-form', [
            'thread' => $post->thread,
            'action' => route('posts.update', $post),
            'method' => 'PUT',
            'initialContent' => $post->content_original,
            'initialFormat' => $post->content_format,
        ])
    </div>
@endsection
