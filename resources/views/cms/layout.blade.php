<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $metaTitle = $metaTitle ?? ($page->seo_title ?? $post->seo_title ?? null);
        $metaDescription = $metaDescription ?? ($page->seo_description ?? $post->seo_description ?? ($post->excerpt ?? null));
        $metaImage = $metaImage ?? ($page->seo_image ?? $post->seo_image ?? null);
        $metaTitle = $metaTitle ?: trim($__env->yieldContent('title')) ?: 'CMS';
    @endphp
    <title>{{ $metaTitle }}</title>
    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if ($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
    @endif
    <meta property="og:title" content="{{ $metaTitle }}">
    @if ($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.15),_transparent_50%)]"></div>
    <header class="relative border-b border-slate-800/70 bg-slate-900/70 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ route('forum.index') }}" class="text-lg font-semibold text-white">Gaming CMS</a>
            <nav class="flex items-center gap-4 text-sm">
                @includeWhen(isset($menuTree), 'cms.partials.menu', ['items' => $menuTree])
                <a href="{{ route('forum.index') }}" class="text-slate-300 hover:text-white">Forum</a>
                <a href="/admin" class="rounded border border-slate-700 px-3 py-1 text-slate-200 hover:border-slate-500">Admin</a>
            </nav>
        </div>
    </header>

    <main class="relative mx-auto max-w-4xl px-4 py-10">
        @yield('content')
    </main>

    <footer class="relative border-t border-slate-800/70 bg-slate-900/60 py-6">
        <div class="mx-auto max-w-4xl px-4 text-xs text-slate-500">
            Gaming CMS · CMS
        </div>
    </footer>
</body>
</html>
