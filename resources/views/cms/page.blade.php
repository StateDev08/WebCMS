@extends('cms.layout')

@section('title', $page->seo_title ?: $page->title)

@section('content')
    @php
        $heroImage = ($page->seo_image_enabled ?? true) ? $page->seo_image : null;
        if (!$heroImage) {
            foreach ($page->blocks ?? [] as $block) {
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
            <div class="text-sm uppercase tracking-widest text-blue-300">Seite</div>
            <h1 class="mt-2 text-3xl font-semibold text-white">{{ $page->title }}</h1>
        </header>
        @include('cms.partials.blocks', ['blocks' => $page->blocks])
    </article>
@endsection
