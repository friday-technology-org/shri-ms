<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    protected $fillable = [
        'field_group_id',
        'label',
        'name',
        'type',
        'instructions',
        'required',
        'settings',
        'sort_order',
    ];

    protected $casts = [
        'required' => 'boolean',
        'settings' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class, 'field_group_id');
    }
}
