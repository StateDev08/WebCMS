@php
    $label = $data['label'] ?? 'Mehr erfahren';
    $url = $data['url'] ?? '#';
    $style = $data['style'] ?? 'primary';
    $classes = $style === 'secondary'
        ? 'border border-slate-600 text-slate-200 hover:border-slate-400'
        : 'bg-emerald-600 text-white hover:bg-emerald-500';
@endphp

<div class="my-4">
    <a href="{{ $url }}" class="inline-flex items-center rounded px-4 py-2 text-sm font-semibold {{ $classes }}">
        {{ $label }}
    </a>
</div>
