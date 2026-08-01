<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Cms\Core\Models\Traits\HasMultisite;

class Post extends Model
{
    use SoftDeletes, HasSlug, HasMultisite;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'post_type',
        'status',
        'metadata',
        'published_at',
        'featured_image_id',
        'site_id',
        'lang',
        'translation_of_id',
    ];

    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relationships', 'post_id', 'term_id');
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function getMeta($key, $default = null)
    {
        $meta = $this->meta()->where('meta_key', $key)->first();
        if (!$meta) {
            return $default;
        }

        $value = $meta->meta_value;
        
        // Try to decode JSON for repeater fields or structured data
        if (is_string($value) && is_array(json_decode($value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * Resolve an ACF image field to a Media model instance.
     * Usage: $post->getMediaMeta('hero_image') → Media|null
     */
    public function getMediaMeta(string $key): ?Media
    {
        $id = $this->getMeta($key);
        if (!$id || !is_numeric($id)) return null;
        return Media::find((int) $id);
    }


    public function updateMeta($key, $value)
    {
        return $this->meta()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    public function deleteMeta($key)
    {
        return $this->meta()->where('meta_key', $key)->delete();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getPermalinkAttribute(): string
    {
        if ($this->post_type === 'page' && cms_option('page_on_front') == $this->id) {
            return url('/');
        }
        if ($this->post_type === 'post' || $this->post_type === 'page') {
            return url($this->slug);
        }
        return url($this->post_type . '/' . $this->slug);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function revisions()
    {
        return $this->hasMany(PostRevision::class)->orderBy('created_at', 'desc');
    }
}
