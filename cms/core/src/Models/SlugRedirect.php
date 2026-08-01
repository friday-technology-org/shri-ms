<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlugRedirect extends Model
{
    protected $fillable = [
        'post_id',
        'old_slug',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
