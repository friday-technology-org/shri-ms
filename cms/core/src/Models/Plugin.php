<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'description',
        'author',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
