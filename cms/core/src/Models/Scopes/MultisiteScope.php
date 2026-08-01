<?php

namespace Cms\Core\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class MultisiteScope implements Scope
{
    protected static ?int $currentSiteId = null;

    public function apply(Builder $builder, Model $model)
    {
        // Only partition if multisite feature is active and we have determined a site ID
        $siteId = self::getCurrentSiteId();
        if ($siteId && Schema::hasColumn($model->getTable(), 'site_id')) {
            $builder->where($model->getTable() . '.site_id', $siteId);
        }
    }

    public static function getCurrentSiteId(): ?int
    {
        if (self::$currentSiteId !== null) {
            return self::$currentSiteId;
        }

        if (!config('cms.multisite', false) && !env('MULTISITE_ENABLED', false)) {
            return 1; // Fallback to default site
        }

        try {
            $host = request()->getHost();
            $path = request()->segment(1);

            // Lookup by domain or path prefix
            $site = \Illuminate\Support\Facades\DB::table('cms_sites')
                ->where('domain', $host)
                ->orWhere('path', $path)
                ->where('is_active', true)
                ->first();

            if ($site) {
                self::$currentSiteId = $site->id;
            } else {
                self::$currentSiteId = 1; // default fallback
            }
        } catch (\Throwable $e) {
            self::$currentSiteId = 1;
        }

        return self::$currentSiteId;
    }

    public static function setCurrentSiteId(int $id): void
    {
        self::$currentSiteId = $id;
    }
}
