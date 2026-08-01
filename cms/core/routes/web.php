<?php

use Cms\Core\Http\Controllers\Auth\LoginController;
use Cms\Core\Http\Controllers\DashboardController;
use Cms\Core\Http\Controllers\InstallController;
use Cms\Core\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    // CMS Admin Routes
    Route::prefix('admin')->group(function () {
        // Guest Auth Routes
        Route::middleware('guest')->group(function () {
            Route::get('login', [LoginController::class, 'showLoginForm'])->name('cms.login');
            Route::post('login', [LoginController::class, 'login']);
        });

        // Protected Auth Routes
        Route::middleware('auth')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('cms.dashboard');
            Route::post('logout', [LoginController::class, 'logout'])->name('cms.logout');

            // Profile (All authenticated users can access their profile)
            Route::get('profile', [\Cms\Core\Http\Controllers\ProfileController::class, 'edit'])->name('cms.profile.edit');
            Route::put('profile', [\Cms\Core\Http\Controllers\ProfileController::class, 'update'])->name('cms.profile.update');

            // API Tokens (All authenticated users can generate/manage API tokens)
            Route::get('profile/api-tokens', [\Cms\Core\Http\Controllers\ApiController::class, 'getTokens'])->name('cms.api-tokens.index');
            Route::post('profile/api-tokens', [\Cms\Core\Http\Controllers\ApiController::class, 'generateToken'])->name('cms.api-tokens.store');
            Route::delete('profile/api-tokens/{id}', [\Cms\Core\Http\Controllers\ApiController::class, 'revokeToken'])->name('cms.api-tokens.destroy');

            // === ADMINISTRATOR & EDITOR & CONTRIBUTOR ===
            Route::middleware('role:Administrator|Editor|Contributor')->group(function () {
                // Posts
                Route::resource('posts', \Cms\Core\Http\Controllers\PostController::class)->names('cms.posts');
            });

            // === ADMINISTRATOR & EDITOR ===
            Route::middleware('role:Administrator|Editor')->group(function () {
                // Pages
                Route::resource('pages', \Cms\Core\Http\Controllers\PageController::class)->names('cms.pages')->parameters(['pages' => 'page']);

                // Generic Content Controller (Dynamic CPTs)
                Route::get('c/{post_type}', [\Cms\Core\Http\Controllers\ContentController::class, 'index'])->name('cms.content.index');
                Route::get('c/{post_type}/create', [\Cms\Core\Http\Controllers\ContentController::class, 'create'])->name('cms.content.create');
                Route::post('c/{post_type}', [\Cms\Core\Http\Controllers\ContentController::class, 'store'])->name('cms.content.store');
                Route::get('c/{post_type}/{content}/edit', [\Cms\Core\Http\Controllers\ContentController::class, 'edit'])->name('cms.content.edit');
                Route::put('c/{post_type}/{content}', [\Cms\Core\Http\Controllers\ContentController::class, 'update'])->name('cms.content.update');
                Route::delete('c/{post_type}/{content}', [\Cms\Core\Http\Controllers\ContentController::class, 'destroy'])->name('cms.content.destroy');
                Route::get('c/{post_type}/{content}/revisions', [\Cms\Core\Http\Controllers\ContentController::class, 'revisions'])->name('cms.content.revisions');
                Route::post('c/{post_type}/{content}/revisions/{revision}/restore', [\Cms\Core\Http\Controllers\ContentController::class, 'restoreRevision'])->name('cms.content.revisions.restore');

                // Media Library
                Route::get('media', [MediaController::class, 'index'])->name('cms.media.index');
                Route::get('media/items', [MediaController::class, 'items'])->name('cms.media.items');
                Route::post('media/upload', [MediaController::class, 'upload'])->name('cms.media.upload');
                Route::get('media/{media}', [MediaController::class, 'show'])->name('cms.media.show');
                Route::put('media/{media}', [MediaController::class, 'update'])->name('cms.media.update');
                Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('cms.media.destroy');

                // Comments Admin
                Route::get('comments', [\Cms\Core\Http\Controllers\CommentController::class, 'index'])->name('cms.comments.index');
                Route::post('comments/{comment}/approve', [\Cms\Core\Http\Controllers\CommentController::class, 'approve'])->name('cms.comments.approve');
                Route::post('comments/{comment}/spam', [\Cms\Core\Http\Controllers\CommentController::class, 'spam'])->name('cms.comments.spam');
                Route::delete('comments/{comment}', [\Cms\Core\Http\Controllers\CommentController::class, 'destroy'])->name('cms.comments.destroy');
            });

            // === ADMINISTRATOR ONLY ===
            Route::middleware('role:Administrator')->group(function () {
                // Users
                Route::resource('users', \Cms\Core\Http\Controllers\UserController::class)->names('cms.users');

                // Field Groups
                Route::resource('field-groups', \Cms\Core\Http\Controllers\FieldGroupController::class)->names('cms.field-groups');

                // Custom Post Types (CPTs) Builder
                Route::resource('post-types', \Cms\Core\Http\Controllers\PostTypeController::class)->names('cms.post-types')->parameters(['post-types' => 'postType']);

                // Taxonomies
                Route::resource('taxonomies', \Cms\Core\Http\Controllers\TaxonomyController::class)->names('cms.taxonomies');
                
                // Terms (Nested under Taxonomy)
                Route::resource('taxonomies.terms', \Cms\Core\Http\Controllers\TermController::class)->names('cms.terms')->except(['create', 'show']);

                // Appearance: Themes
                Route::get('appearance/themes', [\Cms\Core\Http\Controllers\ThemeController::class, 'index'])->name('cms.themes.index');
                Route::post('appearance/themes', [\Cms\Core\Http\Controllers\ThemeController::class, 'store'])->name('cms.themes.store');
                Route::post('appearance/themes/{themeSlug}/activate', [\Cms\Core\Http\Controllers\ThemeController::class, 'activate'])->name('cms.themes.activate');
                Route::delete('appearance/themes/{themeSlug}', [\Cms\Core\Http\Controllers\ThemeController::class, 'destroy'])->name('cms.themes.destroy');
                Route::get('appearance/themes/{themeSlug}/preview', [\Cms\Core\Http\Controllers\ThemeController::class, 'preview'])->name('cms.themes.preview');

                // Appearance: Customizer
                Route::get('appearance/customizer', [\Cms\Core\Http\Controllers\AppearanceController::class, 'edit'])->name('cms.customizer.edit');
                Route::post('appearance/customizer', [\Cms\Core\Http\Controllers\AppearanceController::class, 'update'])->name('cms.customizer.update');
                Route::post('appearance/customizer/favicon', [\Cms\Core\Http\Controllers\AppearanceController::class, 'favicon'])->name('cms.customizer.favicon');

                // Appearance: Menus
                Route::get('appearance/menus', [\Cms\Core\Http\Controllers\MenuController::class, 'index'])->name('cms.menus.index');
                Route::post('appearance/menus', [\Cms\Core\Http\Controllers\MenuController::class, 'store'])->name('cms.menus.store');
                Route::get('appearance/menus/lookup/items', [\Cms\Core\Http\Controllers\MenuController::class, 'itemLookup'])->name('cms.menus.item-lookup');
                Route::get('appearance/menus/{menu}', [\Cms\Core\Http\Controllers\MenuController::class, 'edit'])->name('cms.menus.edit');
                Route::put('appearance/menus/{menu}', [\Cms\Core\Http\Controllers\MenuController::class, 'update'])->name('cms.menus.update');
                Route::delete('appearance/menus/{menu}', [\Cms\Core\Http\Controllers\MenuController::class, 'destroy'])->name('cms.menus.destroy');

                // Appearance: Widgets
                Route::get('appearance/widgets', [\Cms\Core\Http\Controllers\WidgetController::class, 'index'])->name('cms.widgets.index');
                Route::post('appearance/widgets', [\Cms\Core\Http\Controllers\WidgetController::class, 'store'])->name('cms.widgets.store');
                Route::put('appearance/widgets/{widget}', [\Cms\Core\Http\Controllers\WidgetController::class, 'update'])->name('cms.widgets.update');
                Route::delete('appearance/widgets/{widget}', [\Cms\Core\Http\Controllers\WidgetController::class, 'destroy'])->name('cms.widgets.destroy');
                Route::post('appearance/widgets/reorder', [\Cms\Core\Http\Controllers\WidgetController::class, 'reorder'])->name('cms.widgets.reorder');

                // Plugins
                Route::get('plugins', [\Cms\Core\Http\Controllers\PluginController::class, 'index'])->name('cms.plugins.index');
                Route::post('plugins/upload', [\Cms\Core\Http\Controllers\PluginController::class, 'upload'])->name('cms.plugins.upload');
                Route::post('plugins/{slug}/activate', [\Cms\Core\Http\Controllers\PluginController::class, 'activate'])->name('cms.plugins.activate');
                Route::post('plugins/{slug}/deactivate', [\Cms\Core\Http\Controllers\PluginController::class, 'deactivate'])->name('cms.plugins.deactivate');
                Route::delete('plugins/{slug}', [\Cms\Core\Http\Controllers\PluginController::class, 'destroy'])->name('cms.plugins.destroy');

                // Settings & Tools
                Route::get('settings', [\Cms\Core\Http\Controllers\SettingsController::class, 'index'])->name('cms.settings.index');
                Route::post('settings', [\Cms\Core\Http\Controllers\SettingsController::class, 'update'])->name('cms.settings.update');
                Route::post('settings/export', [\Cms\Core\Http\Controllers\SettingsController::class, 'exportData'])->name('cms.settings.export');
                Route::post('settings/erase', [\Cms\Core\Http\Controllers\SettingsController::class, 'eraseData'])->name('cms.settings.erase');
                Route::get('settings/logs-404', [\Cms\Core\Http\Controllers\Log404Controller::class, 'index'])->name('cms.settings.logs404');
                Route::delete('settings/logs-404', [\Cms\Core\Http\Controllers\Log404Controller::class, 'destroyAll'])->name('cms.settings.logs404.destroy');

                // Phase 9: Settings Sub-sections
                Route::get('settings/translations', [\Cms\Core\Http\Controllers\TranslationController::class, 'index'])->name('cms.translations.index');
                Route::post('settings/translations/create', [\Cms\Core\Http\Controllers\TranslationController::class, 'create'])->name('cms.translations.create');
                Route::get('settings/translations/{locale}', [\Cms\Core\Http\Controllers\TranslationController::class, 'edit'])->name('cms.translations.edit');
                Route::post('settings/translations/{locale}/update', [\Cms\Core\Http\Controllers\TranslationController::class, 'update'])->name('cms.translations.update');

                Route::get('settings/network', [\Cms\Core\Http\Controllers\NetworkController::class, 'index'])->name('cms.network.index');
                Route::post('settings/network', [\Cms\Core\Http\Controllers\NetworkController::class, 'store'])->name('cms.network.store');
                Route::post('settings/network/{id}/toggle', [\Cms\Core\Http\Controllers\NetworkController::class, 'toggleActive'])->name('cms.network.toggle');
                Route::delete('settings/network/{id}', [\Cms\Core\Http\Controllers\NetworkController::class, 'destroy'])->name('cms.network.destroy');

                Route::get('settings/updates', [\Cms\Core\Http\Controllers\UpgradeController::class, 'index'])->name('cms.updates.index');
                Route::post('settings/updates/run', [\Cms\Core\Http\Controllers\UpgradeController::class, 'upgrade'])->name('cms.updates.run');


                // Tools & Diagnostics
                Route::get('tools/site-health', [\Cms\Core\Http\Controllers\DiagnosticsController::class, 'siteHealth'])->name('cms.tools.site-health');
                Route::post('tools/site-health/maintenance', [\Cms\Core\Http\Controllers\DiagnosticsController::class, 'dbMaintenance'])->name('cms.tools.maintenance');
                Route::get('tools/activity-logs', [\Cms\Core\Http\Controllers\DiagnosticsController::class, 'activityLogs'])->name('cms.tools.activity-logs');
                Route::get('tools/backups', [\Cms\Core\Http\Controllers\BackupController::class, 'index'])->name('cms.tools.backups.index');
                Route::post('tools/backups', [\Cms\Core\Http\Controllers\BackupController::class, 'store'])->name('cms.tools.backups.store');
                Route::get('tools/backups/{filename}/download', [\Cms\Core\Http\Controllers\BackupController::class, 'download'])->name('cms.tools.backups.download');
                Route::delete('tools/backups/{filename}', [\Cms\Core\Http\Controllers\BackupController::class, 'destroy'])->name('cms.tools.backups.destroy');
            });
        });
    });

    // Public API endpoints (Protected by API tokens)
    Route::prefix('api/v1')->middleware('cms.api.auth')->group(function () {
        Route::get('posts', [\Cms\Core\Http\Controllers\ApiController::class, 'posts']);
        Route::get('posts/{id}', [\Cms\Core\Http\Controllers\ApiController::class, 'post']);
        Route::get('pages', [\Cms\Core\Http\Controllers\ApiController::class, 'pages']);
        Route::get('terms/{taxonomy}', [\Cms\Core\Http\Controllers\ApiController::class, 'terms']);
        Route::get('media', [\Cms\Core\Http\Controllers\ApiController::class, 'media']);
        Route::get('comments', [\Cms\Core\Http\Controllers\ApiController::class, 'comments']);
        Route::get('settings', [\Cms\Core\Http\Controllers\ApiController::class, 'settings']);
    });

    // Public GraphQL endpoint
    Route::post('graphql', [\Cms\Core\Http\Controllers\GraphqlController::class, 'handle']);

    // Frontend Comment Submission Route
    Route::post('comments', [\Cms\Core\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

    // Dynamic Sitemaps & robots.txt
    Route::get('sitemap.xml', [\Cms\Core\Http\Controllers\SeoSitemapController::class, 'sitemap']);
    Route::get('robots.txt', [\Cms\Core\Http\Controllers\SeoSitemapController::class, 'robots']);

    Route::prefix('install')->group(function () {
        Route::get('/', [InstallController::class, 'step1_requirements'])->name('install.step1');
        Route::get('/database', [InstallController::class, 'step2_database'])->name('install.step2');
        Route::post('/database/test', [InstallController::class, 'testDatabaseConnection'])->name('install.test_db');
        Route::post('/database/save', [InstallController::class, 'saveDatabase'])->name('install.save_db');
        Route::get('/site', [InstallController::class, 'step3_site'])->name('install.step3');
        Route::post('/process', [InstallController::class, 'processInstall'])->name('install.process');
        Route::get('/finish', [InstallController::class, 'step4_finish'])->name('install.finish');
    });

    // Frontend Catch-All Routing & Permalinks Engine
    Route::middleware(['cms.locale'])->group(function() {
        Route::fallback([\Cms\Core\Http\Controllers\FrontendController::class, 'catchAll']);
    });
});
