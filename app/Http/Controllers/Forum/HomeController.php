<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumThread;
use App\Models\Game;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::query()
            ->active()
            ->ordered()
            ->withCount(['communities', 'categories'])
            ->get();

        $latestThreads = ForumThread::query()
            ->with(['category', 'author', 'community'])
            ->latest()
            ->take(10)
            ->get();

        return view('forum.index', compact('games', 'latestThreads'));
    }
}
