<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ForumPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'parent_id',
        'content_original',
        'content_format',
        'content_html',
        'content_bbcode_cache',
        'content_markdown_cache',
        'is_edited',
        'edited_at',
        'edited_by',
    ];

    protected function casts(): array
    {
        return [
            'is_edited' => 'boolean',
            'edited_at' => 'datetime',
        ];
    }

    // Relationships
    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function parent()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }

    public function attachments()
    {
        return $this->hasMany(ForumAttachment::class, 'post_id');
    }

    public function reactions()
    {
        return $this->hasMany(PostReaction::class, 'post_id');
    }

    // Helper Methods
    public function getContent(string $format = null): string
    {
        if ($format === null) {
            return $this->content_html;
        }

        if ($format === $this->content_format) {
            return $this->content_original;
        }

        // Return cached conversion if available
        $cacheField = "content_{$format}_cache";
        if ($this->$cacheField !== null) {
            return $this->$cacheField;
        }

        return $this->content_original;
    }

    public function isBBCode(): bool
    {
        return $this->content_format === 'bbcode';
    }

    public function isMarkdown(): bool
    {
        return $this->content_format === 'markdown';
    }

    public function canEdit(User $user): bool
    {
        if ($user->id === $this->user_id) {
            return true;
        }

        $community = $this->thread?->community;
        if ($community && $user->can('moderate', $community)) {
            return true;
        }

        return $user->can('manage-forum');
    }

    public function canDelete(User $user): bool
    {
        if ($user->id === $this->user_id) {
            return true;
        }

        $community = $this->thread?->community;
        if ($community && $user->can('moderate', $community)) {
            return true;
        }

        return $user->can('manage-forum');
    }
}
