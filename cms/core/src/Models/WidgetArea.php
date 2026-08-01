<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WidgetArea extends Model
{
    protected $fillable = [
        'key',
        'label',
        'description',
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'area_key', 'key')->orderBy('sort_order');
    }
}
