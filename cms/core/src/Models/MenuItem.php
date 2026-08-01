<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'type',
        'object_id',
        'label',
        'url',
        'target_blank',
        'css_class',
        'rel_nofollow',
        'is_mega_menu',
        'mega_menu_settings',
        'sort_order',
    ];

    protected $casts = [
        'target_blank' => 'boolean',
        'rel_nofollow' => 'boolean',
        'is_mega_menu' => 'boolean',
        'mega_menu_settings' => 'array',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Resolve the front-end URL for this item based on its type.
     */
    public function resolvedUrl(): string
    {
        return match ($this->type) {
            'post' => ($post = Post::find($this->object_id)) ? $post->permalink : '#',
            'term' => ($term = Term::find($this->object_id)) ? url($term->slug) : '#',
            default => $this->url ?: '#',
        };
    }
}
