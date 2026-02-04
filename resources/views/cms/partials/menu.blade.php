@php
    $currentPath = $currentPath ?? trim(request()->path(), '/');
    $level = $level ?? 0;
    $hasActive = function ($item) use (&$hasActive, $currentPath) {
        $url = $item->url ?? '#';
        $path = $url !== '#' ? trim(parse_url($url, PHP_URL_PATH) ?? '', '/') : '';
        if ($path !== '' && $currentPath === $path) {
            return true;
        }
        foreach (($item->children ?? collect()) as $child) {
            if ($hasActive($child)) {
                return true;
            }
        }
        return false;
    };
@endphp

@foreach ($items as $item)
    @php
        $url = $item->url ?? '#';
        $isActive = $hasActive($item);
    @endphp
    @if ($level === 0)
        <div class="group relative">
            <a href="{{ $url }}"
               class="{{ $isActive ? 'text-emerald-400' : 'text-slate-300 hover:text-white' }} inline-flex items-center gap-1">
                {{ $item->label }}
                @if (($item->children ?? collect())->count() > 0)
                    <span class="text-xs text-slate-400">▾</span>
                @endif
            </a>
            @if (($item->children ?? collect())->count() > 0)
                <div class="invisible absolute left-0 top-full z-10 mt-2 w-56 rounded border border-slate-800/70 bg-slate-900/90 p-2 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100">
                    @include('cms.partials.menu', [
                        'items' => $item->children,
                        'currentPath' => $currentPath,
                        'level' => $level + 1,
                    ])
                </div>
            @endif
        </div>
    @else
        <div>
            <a href="{{ $url }}"
               class="{{ $isActive ? 'text-emerald-400' : 'text-slate-300 hover:text-white' }} block rounded px-2 py-1 text-sm">
                <span class="{{ $level > 1 ? 'ml-2' : '' }}">{{ $item->label }}</span>
            </a>
            @if (($item->children ?? collect())->count() > 0)
                <div class="ml-2 border-l border-slate-800/70 pl-2">
                    @include('cms.partials.menu', [
                        'items' => $item->children,
                        'currentPath' => $currentPath,
                        'level' => $level + 1,
                    ])
                </div>
            @endif
        </div>
    @endif
@endforeach
