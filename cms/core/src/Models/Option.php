<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Option extends Model
{
    protected $table = 'cms_options';

    protected $fillable = [
        'option_name',
        'option_value',
        'autoload',
    ];

    protected function casts(): array
    {
        return [
            'autoload' => 'boolean',
        ];
    }

    /**
     * Retrieve an option value by key.
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        $cacheKey = "cms_option_{$name}";

        return Cache::remember($cacheKey, 3600, function () use ($name, $default) {
            $record = static::where('option_name', $name)->first();

            if (!$record) {
                return $default;
            }

            $value = $record->option_value;
            $decoded = json_decode($value, true);

            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;
        });
    }

    /**
     * Store or update an option value. A null value clears the option
     * entirely (rather than being stringified to '' and later read back
     * as the string '' instead of null).
     */
    public static function set(string $name, mixed $value, bool $autoload = true): static|bool
    {
        if ($value === null) {
            return static::forget($name);
        }

        $stringValue = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

        $option = static::updateOrCreate(
            ['option_name' => $name],
            [
                'option_value' => $stringValue,
                'autoload' => $autoload,
            ]
        );

        Cache::forget("cms_option_{$name}");

        return $option;
    }

    /**
     * Delete an option.
     */
    public static function forget(string $name): bool
    {
        Cache::forget("cms_option_{$name}");
        return (bool) static::where('option_name', $name)->delete();
    }
}
