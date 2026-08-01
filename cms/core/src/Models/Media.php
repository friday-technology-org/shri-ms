<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'original_filename',
        'filename',
        'path',
        'disk',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'alt_text',
        'caption',
        'description',
        'sizes',
    ];

    protected $casts = [
        'sizes' => 'array',
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Return the public URL for the original file.
     */
    public function url(): string
    {
        return url('uploads/' . $this->path);
    }

    /**
     * Return a generated thumbnail URL by size key.
     * Falls back to original if size not available.
     */
    public function thumbnailUrl(string $size = 'thumbnail'): string
    {
        if ($this->sizes && isset($this->sizes[$size])) {
            return url('uploads/' . $this->sizes[$size]);
        }
        return $this->url();
    }

    /**
     * Human-readable file size.
     */
    public function humanSize(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1_048_576) return round($bytes / 1_048_576, 1) . ' MB';
        if ($bytes >= 1_024) return round($bytes / 1_024, 1) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Check if this media item is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if this media item is a video.
     */
    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    /**
     * Check if this media item is audio.
     */
    public function isAudio(): bool
    {
        return str_starts_with($this->mime_type, 'audio/');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'like', 'video/%');
    }

    public function scopeAudio($query)
    {
        return $query->where('mime_type', 'like', 'audio/%');
    }

    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'not like', 'image/%')
                     ->where('mime_type', 'not like', 'video/%')
                     ->where('mime_type', 'not like', 'audio/%');
    }
}
