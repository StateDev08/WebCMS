<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumThread;
use App\Models\ForumCategory;
use App\Services\ContentRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    public function show(ForumThread $thread)
    {
        $thread->load(['category', 'author', 'community', 'game']);

        $posts = $thread->posts()
            ->with('author')
            ->orderBy('created_at')
            ->paginate(15);

        $thread->incrementViews();

        return view('forum.thread', compact('thread', 'posts'));
    }

    public function edit(ForumThread $thread)
    {
        $user = auth()->user();
        if (!$user || !$thread->canEdit($user)) {
            abort(403);
        }

        return view('forum.thread-edit', compact('thread'));
    }

    public function update(Request $request, ForumThread $thread)
    {
        $user = $request->user();
        if (!$user || !$thread->canEdit($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:180'],
        ]);

        if ($validated['title'] !== $thread->title) {
            $thread->update([
                'title' => $validated['title'],
                'slug' => $this->uniqueSlug($validated['title'], $thread->category_id),
            ]);
        }

        return redirect()
            ->route('threads.show', $thread)
            ->with('status', 'Thread wurde aktualisiert.');
    }

    public function destroy(ForumThread $thread)
    {
        $user = auth()->user();
        if (!$user || !$thread->canDelete($user)) {
            abort(403);
        }

        DB::transaction(function () use ($thread) {
            $thread->posts()->delete();
            $thread->delete();
        });

        return redirect()
            ->route('categories.show', $thread->category_id)
            ->with('status', 'Thread wurde gelöscht.');
    }

    public function storePost(Request $request, ForumThread $thread, ContentRenderer $renderer)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        if (!$thread->canPost($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'content_original' => ['required', 'string', 'min:3'],
            'content_format' => ['required', 'in:bbcode,markdown'],
        ]);

        $post = DB::transaction(function () use ($thread, $user, $validated, $renderer) {
            $post = ForumPost::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'content_original' => $validated['content_original'],
                'content_format' => $validated['content_format'],
            ]);

            $renderer->renderAndCache($post);

            $thread->update([
                'posts_count' => $thread->posts()->count(),
                'last_post_id' => $post->id,
                'last_post_at' => $post->created_at,
            ]);

            return $post;
        });

        return redirect()
            ->route('threads.show', $thread)
            ->with('status', 'Beitrag wurde erstellt.');
    }

    public function editPost(ForumPost $post)
    {
        $user = auth()->user();
        if (!$user || !$post->canEdit($user)) {
            abort(403);
        }

        $post->load('thread');

        return view('forum.post-edit', compact('post'));
    }

    public function updatePost(Request $request, ForumPost $post, ContentRenderer $renderer)
    {
        $user = $request->user();
        if (!$user || !$post->canEdit($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'content_original' => ['required', 'string', 'min:3'],
            'content_format' => ['required', 'in:bbcode,markdown'],
        ]);

        $post->update([
            'content_original' => $validated['content_original'],
            'content_format' => $validated['content_format'],
            'is_edited' => true,
            'edited_at' => now(),
            'edited_by' => $user->id,
        ]);

        $renderer->invalidateCache($post);
        $renderer->renderAndCache($post);

        return redirect()
            ->route('threads.show', $post->thread_id)
            ->with('status', 'Beitrag wurde aktualisiert.');
    }

    public function destroyPost(ForumPost $post)
    {
        $user = auth()->user();
        if (!$user || !$post->canDelete($user)) {
            abort(403);
        }

        $thread = $post->thread;

        DB::transaction(function () use ($post, $thread) {
            $post->delete();

            $lastPost = $thread->posts()->latest()->first();
            $thread->update([
                'posts_count' => $thread->posts()->count(),
                'last_post_id' => $lastPost?->id,
                'last_post_at' => $lastPost?->created_at,
            ]);
        });

        return redirect()
            ->route('threads.show', $thread)
            ->with('status', 'Beitrag wurde gelöscht.');
    }

    public function create(Request $request)
    {
        $categories = ForumCategory::query()
            ->where('is_active', true)
            ->where('is_private', false)
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = $categories->firstWhere('id', (int) $request->query('category'));

        return view('forum.thread-create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request, ContentRenderer $renderer)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'category_id' => ['required', 'integer', 'exists:forum_categories,id'],
            'content_original' => ['required', 'string', 'min:3'],
            'content_format' => ['required', 'in:bbcode,markdown'],
        ]);

        $category = ForumCategory::query()
            ->where('id', $validated['category_id'])
            ->where('is_active', true)
            ->where('is_private', false)
            ->firstOrFail();

        $thread = DB::transaction(function () use ($validated, $user, $category, $renderer) {
            $slug = $this->uniqueSlug($validated['title'], $category->id);

            $thread = ForumThread::create([
                'category_id' => $category->id,
                'game_id' => $category->game_id,
                'community_id' => $category->community_id,
                'user_id' => $user->id,
                'title' => $validated['title'],
                'slug' => $slug,
            ]);

            $post = ForumPost::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'content_original' => $validated['content_original'],
                'content_format' => $validated['content_format'],
            ]);

            $renderer->renderAndCache($post);

            $thread->update([
                'posts_count' => 1,
                'last_post_id' => $post->id,
                'last_post_at' => $post->created_at,
            ]);

            return $thread;
        });

        return redirect()
            ->route('threads.show', $thread)
            ->with('status', 'Thread wurde erstellt.');
    }

    protected function uniqueSlug(string $title, int $categoryId): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'thread';
        }

        $slug = $base;
        $counter = 2;

        while (ForumThread::query()->where('category_id', $categoryId)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
