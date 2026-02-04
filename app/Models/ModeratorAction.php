<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ModeratorAction extends Model
{
    use LogsActivity;

    protected $fillable = [
        'moderator_id',
        'actionable_type',
        'actionable_id',
        'action',
        'reason',
        'notes',
    ];

    // Relationships
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByModerator($query, $userId)
    {
        return $query->where('moderator_id', $userId);
    }

    public function scopeOfAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['action', 'reason', 'notes'])
            ->logOnlyDirty();
    }
}
