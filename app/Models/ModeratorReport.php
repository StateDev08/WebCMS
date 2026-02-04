<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeratorReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'severity',
        'status',
        'escalation_level',
        'escalated_at',
        'assigned_to',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
    ];

    protected function casts(): array
    {
        return [
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    // Relationships
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function assignedModerator()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeEscalated($query)
    {
        return $query->where('status', 'escalated');
    }

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    // Helper Methods
    public function escalate()
    {
        $this->update([
            'escalation_level' => $this->escalation_level + 1,
            'escalated_at' => now(),
            'status' => 'escalated',
        ]);
    }

    public function resolve(User $moderator, string $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_by' => $moderator->id,
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }

    public function assign(User $moderator)
    {
        $this->update([
            'assigned_to' => $moderator->id,
            'status' => 'reviewing',
        ]);
    }
}
