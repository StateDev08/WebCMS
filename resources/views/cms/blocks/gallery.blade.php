@php
    $images = $data['images'] ?? [];
@endphp

@if (count($images) > 0)
    <div class="my-6 grid gap-4 sm:grid-cols-2">
        @foreach ($images as $image)
            <img src="{{ $image['url'] ?? '' }}" alt="{{ $image['alt'] ?? '' }}" class="w-full rounded border border-slate-800">
        @endforeach
    </div>
@endif
