# LaraCMS Plugin Handbook

Welcome to the LaraCMS Plugin Developer Handbook! Plugins allow you to extend the core functionality of LaraCMS without modifying core files.

## Directory Structure

Plugins live inside the `cms-content/plugins/` directory.

```
cms-content/
  └── plugins/
      └── my-custom-plugin/
          ├── plugin.json
          ├── plugin.php
          ├── src/
          │   ├── Controllers/
          │   └── Models/
          ├── database/
          │   └── migrations/
          └── routes/
              └── web.php
```

## `plugin.json`

Every plugin requires a `plugin.json` file in its root directory. This tells LaraCMS how to identify your plugin.

```json
{
    "name": "My Custom Plugin",
    "slug": "my-custom-plugin",
    "description": "Extends LaraCMS with amazing new features.",
    "version": "1.0.0",
    "author": "Your Name"
}
```

## `plugin.php`

The `plugin.php` file is the main entry point for your plugin. It must return a class that extends `Cms\Core\Support\PluginBase`.

```php
<?php

use Cms\Core\Support\PluginBase;

return new class extends PluginBase
{
    /**
     * Run when the plugin is activated.
     */
    public function activate(): void
    {
        // Run migrations, set default options, etc.
        $this->runMigrations();
    }

    /**
     * Run when the plugin is deactivated.
     */
    public function deactivate(): void
    {
        // Clean up cron jobs, etc.
    }

    /**
     * Run when the plugin is completely uninstalled.
     */
    public function uninstall(): void
    {
        // Remove database tables, options, etc.
    }

    /**
     * Run on every request when the plugin is active.
     * This is where you register hooks, post types, etc.
     */
    public function boot(): void
    {
        $this->registerHooks();
        $this->registerPostTypes();
    }
};
```

## Action and Filter Hooks

LaraCMS supports a robust, WordPress-style hook system.

### Actions
Actions allow you to add custom code at specific points in the execution lifecycle.

```php
// Registering an action
add_action('user.registered', function($userArray) {
    // Send a welcome email
});

// Firing an action (in core or your own code)
do_action('user.registered', $user->toArray());
```

### Filters
Filters allow you to intercept, modify, and return data before it is rendered or saved.

```php
// Registering a filter
add_filter('the_content', function($content) {
    return $content . '<p>Appended by My Custom Plugin!</p>';
});

// Applying a filter (in core or your own code)
$content = apply_filters('the_content', $post->content);
```

## Registering Custom Post Types (CPTs)

Plugins can dynamically register new Post Types. Since LaraCMS uses a generic `posts` table (like WordPress), you only need to register the configuration in the database or via the admin UI. The `ContentController` will automatically handle routing and CRUD operations.

You can register a CPT during activation:

```php
public function activate(): void
{
    \Cms\Core\Models\PostType::firstOrCreate([
        'name' => 'portfolio',
        'singular_label' => 'Portfolio',
        'plural_label' => 'Portfolios',
        'icon' => 'folder',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
    ]);
}
```

## Creating Plugins via Artisan

LaraCMS provides an Artisan command to quickly scaffold a new plugin:

```bash
php artisan cms:make-plugin my-new-plugin
```

This will generate the folder structure, a `plugin.json`, and a boilerplate `plugin.php` for you!
