@extends('cms.layout')

@section('title', 'CMS Start')

@section('content')
    @php
        $featured = $featuredPosts->first();
        $heroImage = ($featured?->seo_image_enabled ?? true) ? $featured?->seo_image : null;
        if (!$heroImage && $featured) {
            $heroBlocks = $featuredPosts->first()?->blocks ?? [];
            foreach ($heroBlocks as $block) {
                if (($block['type'] ?? null) === 'image' && !empty($block['data']['url'])) {
                    $heroImage = $block['data']['url'];
                    break;
                }
            }
        }
        $heroStyle = $heroImage ? "background-image: linear-gradient(to right, rgba(15,23,42,0.85), rgba(15,23,42,0.6)), url('{$heroImage}'); background-size: cover; background-position: center;" : null;
    @endphp
    <section class="relative mb-10 overflow-hidden rounded border border-slate-800/70 bg-slate-900/40 p-8" @if($heroStyle) style="{{ $heroStyle }}" @endif>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.25),_transparent_55%)]"></div>
        <div class="relative">
            <div class="text-sm uppercase tracking-widest text-blue-300">CMS</div>
            <h1 class="mt-2 text-4xl font-semibold text-white">Willkommen im Gaming CMS</h1>
            <p class="mt-3 text-slate-400">Verwalte Inhalte, Posts und Seiten – alles an einem Ort.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('cms.posts.index') }}" class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Beiträge ansehen
                </a>
                <a href="/admin" class="rounded border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:border-slate-500">
                    Admin öffnen
                </a>
            </div>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-2">
        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Featured Posts</h2>
                <a href="{{ route('cms.posts.index') }}" class="text-sm text-emerald-400 hover:text-emerald-300">Alle Beiträge</a>
            </div>
            <div class="space-y-3">
                @forelse ($featuredPosts as $post)
                    <a href="{{ route('cms.posts.show', $post->slug) }}" class="block rounded border border-slate-800/70 bg-slate-900/40 p-4 transition hover:border-emerald-500/60 hover:bg-slate-900/70">
                        <div class="text-base font-semibold text-white">{{ $post->title }}</div>
                        @if ($post->excerpt)
                            <div class="mt-1 text-sm text-slate-400">{{ $post->excerpt }}</div>
                        @endif
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Keine Beiträge vorhanden.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Neueste Seiten</h2>
            </div>
            <div class="space-y-3">
                @forelse ($latestPages as $page)
                    <a href="{{ route('cms.pages.show', $page->slug) }}" class="block rounded border border-slate-800/70 bg-slate-900/40 p-4 transition hover:border-blue-400/60 hover:bg-slate-900/70">
                        <div class="text-base font-semibold text-white">{{ $page->title }}</div>
                        <div class="mt-1 text-xs text-slate-400">Aktualisiert {{ $page->updated_at->diffForHumans() }}</div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Keine Seiten vorhanden.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
