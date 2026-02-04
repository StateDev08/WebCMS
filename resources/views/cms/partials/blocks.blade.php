@foreach ($blocks ?? [] as $block)
    @switch($block['type'] ?? null)
        @case('text')
            @include('cms.blocks.text', ['data' => $block['data'] ?? []])
            @break
        @case('image')
            @include('cms.blocks.image', ['data' => $block['data'] ?? []])
            @break
        @case('button')
            @include('cms.blocks.button', ['data' => $block['data'] ?? []])
            @break
        @case('gallery')
            @include('cms.blocks.gallery', ['data' => $block['data'] ?? []])
            @break
        @case('columns')
            @include('cms.blocks.columns', ['data' => $block['data'] ?? []])
            @break
        @default
            @break
    @endswitch
@endforeach
