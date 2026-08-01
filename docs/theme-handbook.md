# LaraCMS Theme Handbook

Welcome to the LaraCMS Theme Developer Handbook! LaraCMS themes use standard Laravel Blade templating mixed with classic WordPress-style concepts (like `theme.json`, the Template Hierarchy, and Page Templates).

## Directory Structure

Themes live inside the `cms-content/themes/` directory.

```text
cms-content/
  └── themes/
      └── my-custom-theme/
          ├── theme.json
          ├── functions.php
          ├── screenshot.png
          ├── Controllers/
          │   └── ExampleController.php
          ├── Models/
          │   └── ExampleModel.php
          ├── Migrations/
          │   └── 2026_01_01_000000_create_theme_examples_table.php
          ├── assets/
          │   ├── css/
          │   │   └── style.css
          │   └── js/
          │       └── script.js
          └── views/
              ├── index.blade.php
              ├── single.blade.php
              ├── page.blade.php
              ├── 404.blade.php
              ├── layouts/
              │   └── app.blade.php
              └── templates/
                  └── landing.blade.php
```

## `theme.json`

Every theme requires a `theme.json` file in its root directory. This tells LaraCMS how to identify your theme.

```json
{
    "name": "My Custom Theme",
    "slug": "my-custom-theme",
    "description": "A beautiful, modern LaraCMS theme.",
    "version": "1.0.0",
    "author": "Your Name"
}
```

## Template Hierarchy

LaraCMS automatically routes frontend requests to the most specific Blade view available in your theme. Views are prefixed with the `theme::` namespace.

When resolving a URL (like a Post, Page, or Custom Post Type), LaraCMS checks your theme's `views/` directory in this order:

### For Pages:
1. `page-{slug}.blade.php` (e.g. `page-about-us.blade.php`)
2. `page.blade.php`
3. `single.blade.php`
4. `index.blade.php` (Fallback)

### For Other Post Types (Posts, Portfolios, etc.):
1. `single-{post_type}.blade.php` (e.g. `single-portfolio.blade.php`)
2. `single.blade.php`
3. `index.blade.php` (Fallback)

### For Archives (Categories/Tags):
1. `archive-{taxonomy}.blade.php` (e.g. `archive-category.blade.php`)
2. `archive.blade.php`
3. `index.blade.php` (Fallback)

## Page Templates

Just like WordPress, LaraCMS supports completely custom Page Templates that users can select from a dropdown in the Admin UI.

To create a Page Template, create a new `.blade.php` file anywhere in your theme (e.g. `views/templates/landing.blade.php`) and add this special comment block at the very top:

```blade
@php
/* Template Name: Landing Page */
@endphp

@extends('theme::layouts.master')
@section('content')
  <!-- Your custom layout -->
@endsection
```

LaraCMS will detect this file, populate it in the **Page Attributes** dropdown in the editor, and securely override the Template Hierarchy to load it when selected. Note that Page Templates can only be applied to the `page` post type.

## MVC within Themes

LaraCMS supports full MVC (Model-View-Controller) development directly inside your themes! 

To prevent class collisions between different themes, LaraCMS automatically generates a dynamic StudlyCase namespace based on your theme's slug (e.g., `namespace Theme\MyCustomTheme\Controllers;`).

Controllers should be explicitly loaded in your `functions.php`:
```php
require_once __DIR__ . '/Controllers/ExampleController.php';
```

## Asset Helpers

LaraCMS themes are exposed to the public through the `public/themes` symlink directory. To easily reference your static assets without hardcoding the theme name, use these built-in helpers inside your Blade views:

- `theme_asset($path)`: Returns the full URL to an asset in the current theme. (e.g., `{{ theme_asset('assets/css/style.css') }}`)
- `get_template_directory_uri()`: Returns the absolute URL to your active theme's root directory.
- `get_media_url($id)`: Returns the absolute URL to an uploaded media item given its ID. (e.g., `{{ get_media_url(12) }}`)

## `functions.php`

If you place a `functions.php` file in the root of your theme, LaraCMS will automatically load it when the theme is active. This is the perfect place to:

- Register Custom Shortcodes
- Enqueue Assets (CSS/JS)
- Add Filters and Actions
- Register Dynamic Menu Locations

```php
<?php

// Add a custom filter
add_filter('the_content', function($content) {
    return $content . '<p>Thanks for reading!</p>';
});

// Register a custom shortcode
add_shortcode('button', function($atts, $content) {
    $url = $atts['url'] ?? '#';
    return "<a href='{$url}' class='btn'>{$content}</a>";
});

// Register dynamic menu locations for your theme
register_nav_menus([
    'primary'       => 'Primary Header Menu',
    'footer_1'      => 'Footer Column 1',
    'footer_2'      => 'Footer Column 2',
    'mobile_drawer' => 'Mobile Drawer Menu',
]);
```

## Creating Themes via Artisan

LaraCMS provides an Artisan command to quickly scaffold a new theme:

```bash
php artisan cms:make:theme my-new-theme
```

This will automatically generate the entire folder structure, `theme.json`, MVC scaffolding (Controllers/Models/Migrations), empty `assets/css` and `assets/js` files, and your master `app.blade.php` layout!

## Theme Developer Scaffolding Commands

Once a theme is created and set as active, you can use specialized Artisan commands to easily scaffold code specifically inside your theme (bypassing the core framework):

- `php artisan cms:theme:make-controller {ControllerName}` - Generates a controller in `cms-content/themes/{active-theme}/Controllers/`.
- `php artisan cms:theme:make-model {ModelName}` - Generates an Eloquent model in `cms-content/themes/{active-theme}/Models/`.
- `php artisan cms:theme:make-migration {migration_name}` - Generates a timestamped database migration in `cms-content/themes/{active-theme}/Migrations/`.

All generated files will be placed into the correct directories and assigned the proper dynamic theme namespace (e.g., `Theme\MyCustomTheme\Controllers`).

### Theme Migrations
You do not need any special commands to run your theme's migrations. Because LaraCMS integrates deeply with Laravel, any migrations located in the active theme's `Migrations/` directory will automatically be detected and executed whenever you run:
```bash
php artisan migrate
```
