<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'type',
    ];

    // Relationships
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
