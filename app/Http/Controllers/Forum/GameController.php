<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\Game;

class GameController extends Controller
{
    public function show(Game $game)
    {
        if (!$game->is_active) {
            abort(404);
        }

        $communities = $game->communities()
            ->active()
            ->get();

        $categories = ForumCategory::query()
            ->where('game_id', $game->id)
            ->whereNull('community_id')
            ->where('is_active', true)
            ->where('is_private', false)
            ->orderBy('sort_order')
            ->get();

        $latestThreads = ForumThread::query()
            ->where('game_id', $game->id)
            ->with(['author', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('forum.game', compact('game', 'communities', 'categories', 'latestThreads'));
    }
}
