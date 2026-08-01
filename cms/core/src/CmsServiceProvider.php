<?php

namespace Cms\Core;

use Cms\Core\Http\Middleware\CheckInstallation;
use Cms\Core\Models\Theme;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // If the CMS is not installed, we must fall back to file-based sessions and cache.
        // Otherwise, Laravel will crash when attempting to read from the non-existent 'sessions' table
        // before the CheckInstallation middleware can even redirect the user to the setup wizard.
        if (!is_cms_installed()) {
            config(['session.driver' => 'file']);
            config(['cache.default' => 'file']);
        }

        // Register the Widget Manager as a singleton
        $this->app->singleton(\Cms\Core\Services\WidgetManager::class, function ($app) {
            return new \Cms\Core\Services\WidgetManager();
        });

        $this->app->singleton(\Cms\Core\Services\HookManager::class, function () {
            return new \Cms\Core\Services\HookManager();
        });

        $this->app->singleton(\Cms\Core\Services\ShortcodeParser::class, function () {
            return new \Cms\Core\Services\ShortcodeParser();
        });

        $this->app->singleton(\Cms\Core\Services\SearchService::class, function () {
            return new \Cms\Core\Services\SearchService();
        });

        $this->app->singleton(\Cms\Core\Services\ShortcodeParser::class, function () {
            return new \Cms\Core\Services\ShortcodeParser();
        });

        $this->app->singleton(\Cms\Core\Services\SeoHelper::class, function () {
            return new \Cms\Core\Services\SeoHelper();
        });

        $this->app->singleton(\Cms\Core\Services\BackupService::class, function () {
            return new \Cms\Core\Services\BackupService();
        });

        // Register Theme Loop Engine
        $this->app->singleton(\Cms\Core\Support\ThemeLoop::class, function () {
            return new \Cms\Core\Support\ThemeLoop();
        });
        // Add an alias for easier resolution
        $this->app->alias(\Cms\Core\Support\ThemeLoop::class, 'cms.theme.loop');

        // Load global CMS helper functions
        if (file_exists(__DIR__ . '/helpers.php')) {
            require_once __DIR__ . '/helpers.php';
        }
        
        // Load WordPress-like theme helper functions
        if (file_exists(__DIR__ . '/theme_helpers.php')) {
            require_once __DIR__ . '/theme_helpers.php';
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Kernel $kernel): void
    {
        // Register core migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Override default auth model
        config(['auth.providers.users.model' => \Cms\Core\Models\User::class]);

        // Register core routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register core views with namespace 'cms-core'
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cms-core');

        // Register cms-logo/cms-favicon/cms-menu/cms-widget-area as anonymous <x-...> components.
        // Also register the same directory under an explicit 'cms-components::' view
        // namespace so the components can @include each other by name (e.g. the
        // recursive menu-item partial) without depending on anonymousComponentPath's
        // internal namespace naming.
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/components', 'cms-components');

        // Register the active theme's Blade views under the 'theme' namespace
        $this->registerActiveTheme();

        // Push CheckInstallation middleware into 'web' middleware group
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', CheckInstallation::class);
        $router->pushMiddlewareToGroup('web', \Cms\Core\Http\Middleware\SecurityHeaders::class);
        $router->pushMiddlewareToGroup('web', \Cms\Core\Http\Middleware\PageCache::class);
        
        $router->aliasMiddleware('cms.api.auth', \Cms\Core\Http\Middleware\AuthenticateCmsApi::class);
        $router->aliasMiddleware('cms.locale', \Cms\Core\Http\Middleware\SetLocale::class);
        $router->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        $router->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        // Register Observers
        \Cms\Core\Models\Post::observe(\Cms\Core\Observers\PostObserver::class);

        // Webhook Observers
        \Cms\Core\Models\Post::created(function ($post) {
            dispatch_cms_webhook('post.created', $post->toArray());
        });
        \Cms\Core\Models\User::created(function ($user) {
            dispatch_cms_webhook('user.registered', $user->toArray());
        });
        \Cms\Core\Models\Comment::created(function ($comment) {
            dispatch_cms_webhook('comment.created', $comment->toArray());
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            \Cms\Core\Models\CmsActivityLog::create([
                'user_id' => $event->user->id,
                'event' => 'user_login',
                'description' => "User {$event->user->name} logged in successfully.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        // Register default filters
        add_filter('the_content', 'do_shortcode', 10);

        // Load active plugins
        $this->app->make(\Cms\Core\Services\PluginManager::class)->loadActivePlugins();

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Cms\Core\Console\Commands\CmsInstallCommand::class,
                \Cms\Core\Console\Commands\CmsUpdateCommand::class,
                \Cms\Core\Console\Commands\CmsMakeThemeCommand::class,
                \Cms\Core\Console\Commands\CmsMakePluginCommand::class,
                \Cms\Core\Console\Commands\CmsMakeCptCommand::class,
                \Cms\Core\Console\Commands\CmsThemeMakeControllerCommand::class,
                \Cms\Core\Console\Commands\CmsThemeMakeModelCommand::class,
                \Cms\Core\Console\Commands\CmsThemeMakeMigrationCommand::class,
                \Cms\Core\Console\Commands\CmsCacheClearCommand::class,
                \Cms\Core\Console\Commands\CmsMediaRegenerateCommand::class,
                \Cms\Core\Console\Commands\CmsUserCreateCommand::class,
                \Cms\Core\Console\Commands\CmsDbExportCommand::class,
            ]);
        }
    }

    /**
     * Resolve and register the active theme's Blade views under the
     * 'theme' namespace, with a default-theme fallback. Guarded so this is
     * safe to run before the themes table is migrated (fresh installs) or
     * with no theme directories on disk yet.
     */
    protected function registerActiveTheme(): void
    {
        $activeTheme = null;

        if (is_cms_installed()) {
            try {
                $previewSlug = request()?->query('cms_preview_theme');
                if ($previewSlug && auth()->check()) {
                    $activeTheme = \Cms\Core\Models\Theme::find($previewSlug);
                }
                $activeTheme ??= \Cms\Core\Models\Theme::find(\Cms\Core\Models\Option::get('active_theme', 'default'));
            } catch (\Throwable $e) {
                // DB unreachable — fall through to default theme on disk
            }
        }

        $defaultPath = base_path('cms-content/themes/default');
        $activePath = $activeTheme ? $activeTheme->path() : $defaultPath;

        if (is_dir($activePath)) {
            $this->loadViewsFrom([$activePath . '/views', $activePath], 'theme');
            
            $functionsPath = $activePath . '/functions.php';
            if (file_exists($functionsPath)) {
                require_once $functionsPath;
            }

            $migrationsPath = $activePath . '/Migrations';
            if (is_dir($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }

        if ($activeTheme?->is_child_of) {
            $parentPath = base_path('cms-content/themes/' . $activeTheme->is_child_of);
            if (is_dir($parentPath)) {
                // Registered second: Laravel's view finder tries namespace paths in
                // registration order, so the child (already registered above) wins
                // and this parent path is only used as a fallback for files the
                // child theme doesn't override.
                $this->loadViewsFrom([$parentPath . '/views', $parentPath], 'theme');
            }
        }

        if (is_dir($defaultPath) && $activePath !== $defaultPath) {
            $this->loadViewsFrom([$defaultPath . '/views', $defaultPath], 'theme');
        }
    }
}
