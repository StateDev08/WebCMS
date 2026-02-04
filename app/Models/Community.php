<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Translatable\HasTranslations;

class Community extends Model
{
    use HasFactory, SoftDeletes, NodeTrait, HasTranslations;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'theme_override',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'theme_override' => 'array',
    ];

    public array $translatable = ['name', 'description'];

    // Relationships
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function memberships()
    {
        return $this->hasMany(CommunityMembership::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'community_memberships')
            ->withPivot(['team_id', 'role', 'joined_at', 'last_activity_at'])
            ->withTimestamps();
    }

    public function moderators()
    {
        return $this->members()->wherePivot('role', 'moderator');
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }

    // Helper Methods
    public function canModerate(User $user): bool
    {
        // Check if user can moderate this community or any parent
        $communityIds = $this->ancestors()->pluck('id')->push($this->id);
        
        return $user->memberships()
            ->whereIn('community_id', $communityIds)
            ->whereIn('role', ['moderator', 'admin'])
            ->exists();
    }
}
