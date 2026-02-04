<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Game extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'banner',
        'website',
        'is_active',
        'sort_order',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'meta' => 'array',
    ];

    public array $translatable = ['name', 'description'];

    // Relationships
    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    public function categories()
    {
        return $this->hasMany(ForumCategory::class);
    }

    public function threads()
    {
        return $this->hasManyThrough(ForumThread::class, ForumCategory::class, 'game_id', 'category_id');
    }

    public function gameProfiles()
    {
        return $this->hasMany(GameProfile::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'game_profiles')
            ->withPivot(['display_name', 'avatar_url', 'rank', 'level', 'total_playtime', 'achievements_count'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
