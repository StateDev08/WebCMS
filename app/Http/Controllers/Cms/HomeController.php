<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPosts = Post::published()->latest()->take(3)->get();
        $latestPages = Page::published()->latest()->take(3)->get();
        $menuTree = $this->buildMenuTree('main');

        return view('cms.home', compact('featuredPosts', 'latestPages', 'menuTree'));
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
