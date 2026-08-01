<?php

use Cms\Core\Models\Option;

if (!function_exists('is_cms_installed')) {
    /**
     * Check if the CMS has been installed.
     */
    function is_cms_installed(): bool
    {
        if (app()->runningUnitTests()) {
            return false;
        }

        $installedFileExists = file_exists(storage_path('app/.installed'));

        try {
            $hasUsers = \Illuminate\Support\Facades\Schema::hasTable('users');
            $hasSessions = \Illuminate\Support\Facades\Schema::hasTable('sessions');

            // If lock file exists but tables are missing, remove lock to trigger install
            if ($installedFileExists && (!$hasUsers || !$hasSessions)) {
                @unlink(storage_path('app/.installed'));
                return false;
            }

            if ($installedFileExists && $hasUsers && $hasSessions) {
                return true;
            }

            // Recover lock file if tables exist but lock file was deleted
            if (!$installedFileExists && $hasUsers && \Illuminate\Support\Facades\DB::table('users')->count() > 0) {
                @file_put_contents(storage_path('app/.installed'), json_encode([
                    'installed_at' => now()->toDateTimeString(),
                    'version' => '1.0.0',
                    'note' => 'recovered'
                ]));
                return true;
            }
        } catch (\Exception $e) {
            // DB connection failed
            if ($installedFileExists) {
                @unlink(storage_path('app/.installed'));
            }
            return false;
        }

        return false;
    }
}

if (!function_exists('cms_option')) {
    /**
     * Get a CMS option value.
     */
    function cms_option(string $name, mixed $default = null): mixed
    {
        if (!is_cms_installed()) {
            return $default;
        }

        try {
            return Option::get($name, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('update_cms_option')) {
    /**
     * Update or create a CMS option value.
     */
    function update_cms_option(string $name, mixed $value, bool $autoload = true): bool
    {
        try {
            Option::set($name, $value, $autoload);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('delete_cms_option')) {
    /**
     * Delete a CMS option.
     */
    function delete_cms_option(string $name): bool
    {
        try {
            return Option::forget($name);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('format_field_value')) {
    /**
     * Recursively format custom field values based on their type definition.
     */
    function format_field_value(array $fieldDef, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $type = $fieldDef['type'] ?? 'text';

        if ($type === 'image' || $type === 'file') {
            if (is_numeric($value)) {
                $media = \Cms\Core\Models\Media::find((int) $value);
                return $media ? $media->url() : null; // Return full URL directly
            }
            return $value;
        }

        if ($type === 'group') {
            if (!is_array($value)) return $value;
            $subFields = $fieldDef['settings']['sub_fields'] ?? [];
            $formatted = [];
            
            foreach ($subFields as $subField) {
                $subName = $subField['name'] ?? null;
                if ($subName && array_key_exists($subName, $value)) {
                    $formatted[$subName] = format_field_value($subField, $value[$subName]);
                }
            }
            return array_merge($value, $formatted);
        }

        if ($type === 'repeater') {
            if (!is_array($value)) return $value;
            $subFields = $fieldDef['settings']['sub_fields'] ?? [];
            $formattedRows = [];

            foreach ($value as $index => $rowValue) {
                if (!is_array($rowValue)) {
                    $formattedRows[$index] = $rowValue;
                    continue;
                }
                $formattedRow = [];
                foreach ($subFields as $subField) {
                    $subName = $subField['name'] ?? null;
                    if ($subName && array_key_exists($subName, $rowValue)) {
                        $formattedRow[$subName] = format_field_value($subField, $rowValue[$subName]);
                    }
                }
                $formattedRows[$index] = array_merge($rowValue, $formattedRow);
            }
            return $formattedRows;
        }

        return $value;
    }
}

if (!function_exists('get_field')) {
    /**
     * Get a custom field (post meta) value for a post.
     * Equivalent to ACF's get_field().
     *
     * @param  string    $key     Field name / meta key
     * @param  int|null  $postId  Post ID — defaults to current route post
     * @return mixed
     */
    function get_field(string $key, ?int $postId = null): mixed
    {
        if (!$postId && function_exists('get_post')) {
            $postId = get_post()?->id;
        }
        
        if (!$postId) return null;

        try {
            $post = \Cms\Core\Models\Post::find($postId);
            if (!$post) return null;
            
            $value = $post->getMeta($key);
            
            if ($value !== null) {
                // Fetch field definition by its name
                $field = \Cms\Core\Models\Field::where('name', $key)->first();
                if ($field) {
                    return format_field_value($field->toArray(), $value);
                }
            }
            return $value;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('get_field_media')) {
    /**
     * Get a Media model from an image-type ACF field.
     * Usage: $media = get_field_media('hero_image', $postId);
     *        echo $media?->url();
     *
     * @param  string    $key     Field name / meta key
     * @param  int|null  $postId  Post ID
     * @return \Cms\Core\Models\Media|null
     */
    function get_field_media(string $key, ?int $postId = null): ?\Cms\Core\Models\Media
    {
        if (!$postId && function_exists('get_post')) {
            $postId = get_post()?->id;
        }
        
        if (!$postId) return null;

        try {
            $post = \Cms\Core\Models\Post::find($postId);
            return $post?->getMediaMeta($key);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('the_field')) {
    /**
     * Echo a custom field value directly.
     * Equivalent to ACF's the_field().
     */
    function the_field(string $key, ?int $postId = null): void
    {
        echo htmlspecialchars((string) get_field($key, $postId));
    }
}

if (!function_exists('cms_logo')) {
    /**
     * Get the public URL for a branding logo set via the Customizer.
     * $type: header | header_dark | footer | header_2x
     */
    function cms_logo(string $type = 'header'): ?string
    {
        try {
            $mediaId = cms_option('customizer_logo_' . $type);
            if (!$mediaId) return null;

            return \Cms\Core\Models\Media::find($mediaId)?->url();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('cms_favicon')) {
    /**
     * Get the generated favicon/site-icon asset URLs, keyed by asset name
     * (favicon_32, apple_touch, android_192, android_512, ico, mask_icon).
     * If a type is provided, returns only the URL for that type.
     */
    function cms_favicon(?string $type = null)
    {
        $favicons = cms_option('customizer_favicons', []);

        if ($type) {
            return $favicons[$type] ?? null;
        }

        return $favicons;
    }
}

if (!function_exists('cms_nav_menu')) {
    /**
     * Render the menu assigned to a given location (primary|top_bar|footer|mobile_drawer).
     * Equivalent to WordPress's wp_nav_menu().
     */
    function cms_nav_menu(string $location): string
    {
        try {
            return (string) \Illuminate\Support\Facades\Blade::render(
                '<x-cms-menu :location="$location" />',
                ['location' => $location]
            );
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (!function_exists('register_nav_menus')) {
    /**
     * Register navigation menu locations for a theme.
     * Equivalent to WordPress's register_nav_menus().
     */
    function register_nav_menus(array $locations): void
    {
        add_filter('cms_menu_locations', function ($existingLocations) use ($locations) {
            return array_merge($existingLocations, $locations);
        });
    }
}

if (!function_exists('cms_menu_items')) {
    /**
     * Get the menu items for a given location as a tree structure (Collection/array).
     * Useful for building custom menu HTML structures in themes.
     */
    function cms_menu_items(string $location)
    {
        try {
            $menu = \Cms\Core\Models\Menu::where('location', $location)->first();
            return $menu ? $menu->tree() : [];
        } catch (\Throwable $e) {
            return [];
        }
    }
}

if (!function_exists('bloginfo')) {
    /**
     * Retrieves information about the current site, similar to WordPress.
     * 
     * @param string $show The information to retrieve (e.g. 'name', 'description')
     */
    function bloginfo(string $show = 'name'): string
    {
        return match ($show) {
            'name' => (string) cms_option('site_title', config('app.name')),
            'description' => (string) cms_option('site_tagline', ''),
            'admin_email' => (string) cms_option('admin_email', ''),
            'language' => (string) app()->getLocale(),
            'url' => (string) url('/'),
            default => '',
        };
    }
}

if (!function_exists('cms_widget_area')) {
    /**
     * Render all active widgets assigned to a given widget area key.
     */
    function cms_widget_area(string $areaKey): string
    {
        try {
            return (string) \Illuminate\Support\Facades\Blade::render(
                '<x-cms-widget-area :area="$area" />',
                ['area' => $areaKey]
            );
        } catch (\Throwable $e) {
            return '';
        }
    }
}

if (!function_exists('cms_customizer_head')) {
    /**
     * Assemble the <head> output for the Customizer's font, color, custom
     * CSS, and header JS settings. Meant to be echoed unescaped in a theme layout.
     */
    function cms_customizer_head(): string
    {
        $font = cms_option('customizer_font', 'Instrument Sans');
        $colorPrimary = cms_option('customizer_color_primary', '#7364DB');
        $colorSecondary = cms_option('customizer_color_secondary', '#111827');
        $customCss = cms_option('customizer_custom_css', '');
        $customJsHeader = cms_option('customizer_custom_js_header', '');

        $fontUrl = 'https://fonts.googleapis.com/css2?family=' . str_replace(' ', '+', $font) . ':wght@400;500;600;700&display=swap';

        $html = '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $html .= '<link rel="stylesheet" href="' . e($fontUrl) . '">';
        $html .= '<style id="cms-customizer-vars">:root{--cms-color-primary:' . e($colorPrimary) . ';--cms-color-secondary:' . e($colorSecondary) . ';--cms-font-family:\'' . e($font) . '\', sans-serif;}</style>';

        if ($customCss) {
            $html .= '<style id="cms-customizer-custom-css">' . $customCss . '</style>';
        }

        if ($customJsHeader) {
            $html .= '<script>' . $customJsHeader . '</script>';
        }

        return $html;
    }
}

if (!function_exists('cms_customizer_footer_scripts')) {
    /**
     * Assemble the Customizer's footer JS output. Echoed unescaped, right
     * before </body> in a theme layout.
     */
    function cms_customizer_footer_scripts(): string
    {
        $customJsFooter = cms_option('customizer_custom_js_footer', '');
        return $customJsFooter ? '<script>' . $customJsFooter . '</script>' : '';
    }
}

if (!function_exists('add_action')) {
    /**
     * Register an action hook callback.
     */
    function add_action(string $hook, callable $callback, int $priority = 10): void
    {
        app(\Cms\Core\Services\HookManager::class)->addAction($hook, $callback, $priority);
    }
}

if (!function_exists('do_action')) {
    /**
     * Trigger an action hook.
     */
    function do_action(string $hook, ...$args): void
    {
        app(\Cms\Core\Services\HookManager::class)->doAction($hook, ...$args);
    }
}

if (!function_exists('add_filter')) {
    /**
     * Register a filter hook callback.
     */
    function add_filter(string $hook, callable $callback, int $priority = 10): void
    {
        app(\Cms\Core\Services\HookManager::class)->addFilter($hook, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * Apply filter hook callbacks to a value.
     */
    function apply_filters(string $hook, mixed $value, ...$args): mixed
    {
        return app(\Cms\Core\Services\HookManager::class)->applyFilters($hook, $value, ...$args);
    }
}

if (!function_exists('add_shortcode')) {
    /**
     * Register a shortcode tag and handler.
     */
    function add_shortcode(string $tag, callable $callback): void
    {
        app(\Cms\Core\Services\ShortcodeParser::class)->register($tag, $callback);
    }
}

if (!function_exists('do_shortcode')) {
    /**
     * Parse shortcodes inside content.
     */
    function do_shortcode(string $content): string
    {
        return app(\Cms\Core\Services\ShortcodeParser::class)->parse($content);
    }
}

if (!function_exists('set_transient')) {
    /**
     * Set a transient value with an expiration time in seconds.
     */
    function set_transient(string $key, mixed $value, int $expiration = 0): bool
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        update_cms_option($optKey, json_encode($value));

        if ($expiration > 0) {
            update_cms_option($timeoutKey, time() + $expiration);
        } else {
            update_cms_option($timeoutKey, 0);
        }

        return true;
    }
}

if (!function_exists('get_transient')) {
    /**
     * Get a transient value if it has not expired yet.
     */
    function get_transient(string $key): mixed
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        $timeout = (int) cms_option($timeoutKey, 0);

        if ($timeout > 0 && time() > $timeout) {
            // Expired -> clean up
            delete_transient($key);
            return null;
        }

        $value = cms_option($optKey);
        return $value !== null ? json_decode($value, true) : null;
    }
}

if (!function_exists('delete_transient')) {
    /**
     * Delete a transient value.
     */
    function delete_transient(string $key): bool
    {
        $optKey = '_transient_' . $key;
        $timeoutKey = '_transient_timeout_' . $key;

        // Delete from database directly (cms_options table has a deleteMeta or delete key? Let's use direct DB delete or option set to null)
        // Wait, does update_cms_option(key, null) delete it? Let's check how option set is implemented. Or we can just set to null, or run a query.
        // Let's run a query to delete them from options table to save space.
        \Illuminate\Support\Facades\DB::table('cms_options')->whereIn('option_name', [$optKey, $timeoutKey])->delete();
        return true;
    }
}

if (!function_exists('dispatch_cms_webhook')) {
    /**
     * Dispatch an outgoing webhook event.
     */
    function dispatch_cms_webhook(string $event, array $payload): void
    {
        try {
            $webhooks = \Cms\Core\Models\CmsWebhook::where('is_active', true)->get();
            foreach ($webhooks as $webhook) {
                if (is_array($webhook->events) && in_array($event, $webhook->events)) {
                    \Cms\Core\Jobs\WebhookJob::dispatch($webhook->url, $payload, $webhook->secret);
                }
            }
        } catch (\Throwable $e) {
            logger()->error('Webhook dispatch failed: ' . $e->getMessage());
        }
    }
}

if (!function_exists('cms_locale')) {
    /**
     * Get the active CMS locale.
     */
    function cms_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('get_media_url')) {
    /**
     * Get the URL for a given media ID.
     *
     * @param int|string|null $mediaId
     * @return string|null
     */
    function get_media_url($mediaId): ?string
    {
        if (!$mediaId) {
            return null;
        }

        if (is_string($mediaId) && filter_var($mediaId, FILTER_VALIDATE_URL)) {
            return $mediaId;
        }

        try {
            return \Cms\Core\Models\Media::find($mediaId)?->url();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
