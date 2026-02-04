@php
    $columns = $data['columns'] ?? [];
@endphp

@if (count($columns) > 0)
    @php
        $count = count($columns);
        $gridClass = $count === 1 ? 'md:grid-cols-1' : ($count === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3');
    @endphp
    <div class="my-6 grid gap-6 {{ $gridClass }}">
        @foreach ($columns as $column)
            <div class="rounded border border-slate-800 bg-slate-900/40 p-4">
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($column['text'] ?? '')) !!}
                </div>
            </div>
        @endforeach
    </div>
@endif
