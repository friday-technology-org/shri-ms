<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    /**
     * Build the full nested item tree (root items with their descendants
     * attached as a 'children' relation) from a single flat query.
     */
    public function tree(): Collection
    {
        return $this->buildTree($this->items()->get(), null);
    }

    protected function buildTree(Collection $items, ?int $parentId): Collection
    {
        return $items->where('parent_id', $parentId)->map(function (MenuItem $item) use ($items) {
            $item->setRelation('children', $this->buildTree($items, $item->id));
            return $item;
        })->values();
    }
}
