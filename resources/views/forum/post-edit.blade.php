@extends('layouts.forum')

@section('title', 'Beitrag bearbeiten')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Beitrag bearbeiten</h1>
    </div>

    @include('forum.partials.post-form', [
        'thread' => $post->thread,
        'action' => route('posts.update', $post),
        'method' => 'PUT',
        'initialContent' => $post->content_original,
        'initialFormat' => $post->content_format,
    ])
@endsection
