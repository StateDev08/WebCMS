<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plugin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'author',
        'namespace',
        'config',
        'is_enabled',
        'is_core',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_enabled' => 'boolean',
            'is_core' => 'boolean',
        ];
    }

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeCore($query)
    {
        return $query->where('is_core', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper Methods
    public function enable()
    {
        $this->update(['is_enabled' => true]);
    }

    public function disable()
    {
        if ($this->is_core) {
            throw new \Exception('Core plugins cannot be disabled');
        }
        $this->update(['is_enabled' => false]);
    }
}
