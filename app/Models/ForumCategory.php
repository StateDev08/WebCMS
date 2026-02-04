<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ForumCategory extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'game_id',
        'community_id',
        'parent_id',
        'name',
        'description',
        'slug',
        'icon',
        'is_active',
        'is_private',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    // Relationships
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function parent()
    {
        return $this->belongsTo(ForumCategory::class, 'parent_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function children()
    {
        return $this->hasMany(ForumCategory::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }
}
