<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumThread;

class CategoryController extends Controller
{
    public function show(ForumCategory $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $category->load(['game', 'community']);

        $threads = ForumThread::query()
            ->where('category_id', $category->id)
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }
}
