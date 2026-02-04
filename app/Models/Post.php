<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'status',
        'published_at',
        'blocks',
        'seo_title',
        'seo_description',
        'seo_image',
        'seo_image_enabled',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'blocks' => 'array',
        'seo_image_enabled' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
