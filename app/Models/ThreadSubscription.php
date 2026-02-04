<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    protected $fillable = [
        'thread_id',
        'user_id',
        'notify_email',
        'notify_push',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'notify_email' => 'boolean',
            'notify_push' => 'boolean',
            'last_read_at' => 'datetime',
        ];
    }

    // Relationships
    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->update(['last_read_at' => now()]);
    }

    public function hasUnread(): bool
    {
        return $this->thread->updated_at > $this->last_read_at;
    }
}
