<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityMembership extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'team_id',
        'role',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Helper Methods
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }
}
