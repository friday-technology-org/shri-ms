<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class CmsSite extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
