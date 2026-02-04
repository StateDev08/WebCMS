<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ForumThread extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'game_id',
        'community_id',
        'user_id',
        'title',
        'slug',
        'is_sticky',
        'is_locked',
        'views_count',
        'posts_count',
    ];

    protected function casts(): array
    {
        return [
            'is_sticky' => 'boolean',
            'is_locked' => 'boolean',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class, 'thread_id');
    }

    // Scopes
    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }

    // Helper Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function canPost(User $user): bool
    {
        if ($this->is_locked) {
            return $user->can('moderate', $this->community);
        }
        return true;
    }

    public function canEdit(User $user): bool
    {
        if ($user->id === $this->user_id) {
            return true;
        }

        if ($this->community && $user->can('moderate', $this->community)) {
            return true;
        }

        return $user->can('manage-forum');
    }

    public function canDelete(User $user): bool
    {
        if ($user->id === $this->user_id) {
            return true;
        }

        if ($this->community && $user->can('moderate', $this->community)) {
            return true;
        }

        return $user->can('manage-forum');
    }
}
