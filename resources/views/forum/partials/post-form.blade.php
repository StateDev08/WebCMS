@if ($errors->any())
    <div class="mb-4 rounded border border-rose-500/40 bg-rose-950/30 px-4 py-3 text-rose-200">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $action = $action ?? route('threads.posts.store', $thread);
    $showTitle = $showTitle ?? false;
    $showCategorySelect = $showCategorySelect ?? false;
    $categories = $categories ?? collect();
    $selectedCategory = $selectedCategory ?? null;
    $initialContent = $initialContent ?? '';
    $initialFormat = $initialFormat ?? 'markdown';
    $method = $method ?? 'POST';
@endphp

<form method="POST" action="{{ $action }}" data-forum-editor data-initial-format="{{ old('content_format', $initialFormat) }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <input type="hidden" name="content_format" id="content_format" value="{{ old('content_format', $initialFormat) }}">

    <div class="mb-3 flex items-center gap-2 text-sm">
        <button type="button" class="rounded border border-slate-700 px-3 py-1 text-slate-200 hover:border-slate-500" data-format-toggle="markdown">
            Markdown
        </button>
        <button type="button" class="rounded border border-slate-700 px-3 py-1 text-slate-200 hover:border-slate-500" data-format-toggle="bbcode">
            BBCode
        </button>
        <span class="text-xs text-slate-500">Tipp: Strg+Enter zum Senden</span>
    </div>

    @if ($showTitle)
        <div class="mb-3">
            <label class="mb-1 block text-sm text-slate-400">Titel</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
        </div>
    @endif

    @if ($showCategorySelect)
        <div class="mb-4">
            <label class="mb-1 block text-sm text-slate-400">Kategorie</label>
            <select name="category_id" class="w-full rounded border border-slate-700 bg-slate-900/40 px-3 py-2 text-sm text-slate-100" required>
                <option value="">Bitte wählen</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $selectedCategory?->id) === $category->id)>
                        {{ $category->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div id="markdown-editor-container" class="editor-panel">
        <textarea id="markdown-editor" name="content_original" rows="8">{{ old('content_original', $initialContent) }}</textarea>
    </div>

    <div id="bbcode-editor-container" class="editor-panel is-hidden">
        <textarea id="bbcode-editor" rows="8">{{ old('content_original', $initialContent) }}</textarea>
    </div>

    <div class="mt-4">
        <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
            Beitrag senden
        </button>
    </div>
</form>
