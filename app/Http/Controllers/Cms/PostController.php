<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latest()->paginate(10);
        $menuTree = $this->buildMenuTree('main');

        return view('cms.posts', compact('posts', 'menuTree'));
    }

    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $menuTree = $this->buildMenuTree('main');

        return view('cms.post', compact('post', 'menuTree'));
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
