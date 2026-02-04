<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ForumAttachment extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'post_id',
        'user_id',
        'filename',
        'mime_type',
        'size',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'download_count' => 'integer',
        ];
    }

    // Relationships
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper Methods
    public function incrementDownloads()
    {
        $this->increment('download_count');
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getHumanSize(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
