<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Cms\Core\Models\Traits\HasMultisite;

class Taxonomy extends Model
{
    use HasSlug, HasMultisite;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'hierarchical',
        'post_types',
        'site_id',
        'lang',
        'translation_of_id',
    ];

    protected $casts = [
        'hierarchical' => 'boolean',
        'post_types' => 'array',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
