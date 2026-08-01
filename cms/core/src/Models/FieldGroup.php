<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldGroup extends Model
{
    protected $fillable = [
        'title',
        'location_rules',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'location_rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class)->orderBy('sort_order');
    }

    /**
     * Get field groups matching the given post type and optional post instance.
     */
    public static function getForPost(string $postType, $post = null)
    {
        return self::where('is_active', true)
            ->with('fields')
            ->orderBy('sort_order')
            ->get()
            ->filter(function($group) use ($postType, $post) {
                $rules = $group->location_rules ?? [];
                if (is_string($rules)) {
                    $rules = json_decode($rules, true) ?: [];
                }
                foreach ($rules as $rule) {
                    $param = $rule['param'] ?? '';
                    $value = $rule['value'] ?? '';
                    
                    if ($param === 'post_type' && $value === $postType) {
                        return true;
                    }
                    
                    if ($post) {
                        if ($param === 'page' && ((string) $post->id === (string) $value || $post->slug === $value)) {
                            return true;
                        }
                        
                        $template = $post->getMeta('_cms_page_template');
                        if ($param === 'page_template' && $template === $value) {
                            return true;
                        }
                    }
                }
                return false;
            });
    }
}
