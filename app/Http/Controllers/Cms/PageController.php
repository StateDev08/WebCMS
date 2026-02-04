<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Support\Collection;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();
        $menuTree = $this->buildMenuTree('main');

        return view('cms.page', compact('page', 'menuTree'));
    }

    protected function buildMenuTree(string $slug): Collection
    {
        $menu = Menu::query()->where('slug', $slug)->with('items')->first();
        if (!$menu) {
            return collect();
        }

        $items = $menu->items->sortBy('sort_order')->values();
        $grouped = $items->groupBy('parent_id');

        $build = function ($parentId) use (&$build, $grouped) {
            return ($grouped[$parentId] ?? collect())->map(function ($item) use (&$build) {
                $item->children = $build($item->id);
                return $item;
            });
        };

        return $build(null);
    }
}
