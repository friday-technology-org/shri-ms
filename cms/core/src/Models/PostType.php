<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PostType extends Model
{
    protected $fillable = [
        'name',
        'singular_label',
        'plural_label',
        'description',
        'icon',
        'is_hierarchical',
        'has_archive',
        'supports'
    ];

    protected $casts = [
        'is_hierarchical' => 'boolean',
        'has_archive' => 'boolean',
        'supports' => 'array'
    ];
}
