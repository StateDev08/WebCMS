<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'display_name',
        'avatar_url',
        'rank',
        'level',
        'total_playtime',
        'achievements_count',
        'stats',
        'custom_fields',
    ];

    protected $casts = [
        'level' => 'integer',
        'total_playtime' => 'integer',
        'achievements_count' => 'integer',
        'stats' => 'array',
        'custom_fields' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Helper Methods
    public function getPlaytimeHumanAttribute(): string
    {
        $hours = floor($this->total_playtime / 60);
        $minutes = $this->total_playtime % 60;
        
        return "{$hours}h {$minutes}m";
    }
}
