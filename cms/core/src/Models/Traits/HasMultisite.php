<?php

namespace Cms\Core\Models\Traits;

use Cms\Core\Models\Scopes\MultisiteScope;

trait HasMultisite
{
    protected static function booted()
    {
        static::addGlobalScope(new MultisiteScope());

        static::creating(function ($model) {
            if (empty($model->site_id)) {
                $model->site_id = MultisiteScope::getCurrentSiteId() ?: 1;
            }
        });
    }

    public function site()
    {
        return $this->belongsTo(\Cms\Core\Models\CmsSite::class, 'site_id');
    }
}
