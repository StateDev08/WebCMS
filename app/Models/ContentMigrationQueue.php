<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMigrationQueue extends Model
{
    protected $fillable = [
        'user_id',
        'from_format',
        'to_format',
        'priority',
        'status',
        'total_items',
        'processed_items',
        'failed_items',
        'scheduled_at',
        'started_at',
        'completed_at',
        'error_log',
    ];

    protected function casts(): array
    {
        return [
            'error_log' => 'array',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority')->orderBy('scheduled_at');
    }

    // Helper Methods
    public function start()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function fail(array $errors = [])
    {
        $this->update([
            'status' => 'failed',
            'error_log' => array_merge($this->error_log ?? [], $errors),
        ]);
    }

    public function incrementProcessed()
    {
        $this->increment('processed_items');
    }

    public function incrementFailed()
    {
        $this->increment('failed_items');
    }

    public function getProgressPercentage(): float
    {
        if ($this->total_items === 0) {
            return 0;
        }
        return round(($this->processed_items / $this->total_items) * 100, 2);
    }
}
