<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\ForumCategory;
use App\Models\ForumThread;

class CommunityController extends Controller
{
    public function show(Community $community)
    {
        if (!$community->is_active) {
            abort(404);
        }

        $community->load('game');

        $categories = ForumCategory::query()
            ->where('community_id', $community->id)
            ->where('is_active', true)
            ->where('is_private', false)
            ->orderBy('sort_order')
            ->get();

        $latestThreads = ForumThread::query()
            ->where('community_id', $community->id)
            ->with(['author', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('forum.community', compact('community', 'categories', 'latestThreads'));
    }
}
