<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Billable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_premium',
        'locale',
        'theme',
        'theme_config',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'theme_config' => 'array',
            'trial_ends_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    // Relationships
    public function gameProfiles()
    {
        return $this->hasMany(GameProfile::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_profiles')
            ->withPivot(['display_name', 'avatar_url', 'rank', 'level'])
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(CommunityMembership::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_memberships')
            ->withPivot(['team_id', 'role', 'joined_at'])
            ->withTimestamps();
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    // Helper Methods
    public function isPremium(): bool
    {
        return $this->is_premium || $this->subscribed('default');
    }

    public function canModerateCommunity(Community $community): bool
    {
        return $community->canModerate($this);
    }
}
