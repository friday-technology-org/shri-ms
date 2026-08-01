<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class CmsWebhook extends Model
{
    protected $fillable = [
        'name',
        'url',
        'events',
        'secret',
        'is_active',
    ];

    protected $casts = [
        'events' => 'json',
        'is_active' => 'boolean',
    ];
}
