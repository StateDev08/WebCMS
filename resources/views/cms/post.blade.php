@extends('cms.layout')

@section('title', $post->seo_title ?: $post->title)

@section('content')
    @php
        $heroImage = ($post->seo_image_enabled ?? true) ? $post->seo_image : null;
        if (!$heroImage) {
            foreach ($post->blocks ?? [] as $block) {
                if (($block['type'] ?? null) === 'image' && !empty($block['data']['url'])) {
                    $heroImage = $block['data']['url'];
                    break;
                }
            }
        }
        $heroStyle = $heroImage ? "background-image: linear-gradient(to right, rgba(15,23,42,0.85), rgba(15,23,42,0.6)), url('{$heroImage}'); background-size: cover; background-position: center;" : null;
    @endphp
    <article class="space-y-6">
        <header class="rounded border border-slate-800/70 bg-slate-900/40 p-6" @if($heroStyle) style="{{ $heroStyle }}" @endif>
            <div class="text-sm uppercase tracking-widest text-blue-300">Beitrag</div>
            <h1 class="mt-2 text-3xl font-semibold text-white">{{ $post->title }}</h1>
            @if ($post->excerpt)
                <p class="mt-3 text-slate-400">{{ $post->excerpt }}</p>
            @endif
        </header>
        @include('cms.partials.blocks', ['blocks' => $post->blocks])
    </article>
@endsection
