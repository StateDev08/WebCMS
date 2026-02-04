<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'community_id',
        'leader_id',
        'name',
        'slug',
        'tag',
        'description',
        'logo',
        'max_members',
        'is_recruiting',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'max_members' => 'integer',
        'is_recruiting' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // Relationships
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(CommunityMembership::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'community_memberships', 'team_id', 'user_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRecruiting($query)
    {
        return $query->where('is_recruiting', true);
    }

    // Helper Methods
    public function isFull(): bool
    {
        return $this->members()->count() >= $this->max_members;
    }

    public function hasSpace(): bool
    {
        return !$this->isFull();
    }
}
