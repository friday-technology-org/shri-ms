<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

LaraCMS is a lightweight, modular content management system built on Laravel, offering a WordPress‑style theming API and plugin architecture. It provides an intuitive way to manage posts, custom post types, taxonomies, and media while leveraging Laravel's powerful features.

## Learning LaraCMS

- Official documentation (add link later)
- Community tutorials and video guides
- Sample projects in the `examples/` directory

If you prefer video learning, check out the LaraCMS video series on YouTube.

## LaraCMS Setup Guide

### Prerequisites
- PHP 8.2+
- Composer
- Docker (or local PHP environment)
- MySQL / SQLite

### Installation

```bash
git clone https://github.com/your-org/lara-cms.git
cd lara-cms
docker-compose up -d   # or php artisan serve
composer install
php artisan key:generate
php artisan migrate --seed
```

### Configuration
Edit `.env` to set database credentials, app URL, etc.

### Running the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see the default site.

## Creating a Theme

1. Inside `cms/core/resources/views/themes/` create a new folder e.g., `my-theme`.
2. Add a `views` directory with Blade templates (e.g., `index.blade.php`).
3. Register the theme in `config/cms.php` (add `'theme' => 'my-theme'`).
4. Use the provided WordPress‑style helpers in your Blade files:

```blade
@while (have_posts())
    @php the_post(); @endphp
    <h1>{{ the_title() }}</h1>
    <div>{{ the_content() }}</div>
@endwhile
```

### Assets
Place CSS/JS in `public/themes/my-theme/` and enqueue them via `cms_enqueue_style()` / `cms_enqueue_script()` helpers (to be implemented).

## Installing Plugins

Plugins live in `cms/plugins/`. A plugin is a standard Laravel package with a service provider.

```bash
php artisan cms:plugin Install MyPlugin
```

Or manually:

```bash
composer require vendor/my-plugin
php artisan vendor:publish --tag=laracms-plugin
php artisan migrate
```

### Plugin Structure
- `src/` – plugin code.
- `src/Providers/MyPluginServiceProvider.php` – registers routes, commands, assets.
- `cms_plugins.php` – optional file to load plugin helpers.

## Building a Plugin Compatible with LaraCMS

1. Create a Laravel package.
2. Require `cms/core` as a dependency.
3. In your service provider, bind any services and publish assets:

```php
public function boot()
{
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'myplugin');
    $this->publishes([
        __DIR__.'/../public' => public_path('vendor/myplugin'),
    ], 'laracms-plugin');
}
```

4. Register your plugin in `config/app.php` or via auto‑discovery.

## Roadmap

- **v1.1** – Widget area API, dynamic sidebars.
- **v1.2** – Full Gutenberg‑style block editor integration.
- **v1.3** – Multisite support, REST API.
- **v2.0** – Headless CMS mode, GraphQL endpoint.

## Contributing to LaraCMS

See the `CONTRIBUTING.md` for guidelines. Follow PSR-12 coding standards, write tests, and run `php artisan test`.

## Developer Documentation

### Page SEO Helper

`get_page_seo(string $slug): array` – Returns SEO meta tags for a page identified by its slug. It uses the `SeoHelper` service to build the meta array (title, description, keywords, robots, url, image, og_type). Returns defaults if the page does not exist.

### Theme Helper Functions

- `cms_logo(string $type = 'header')` – Returns the URL for a customizer logo ('header', 'header_dark', 'footer', 'header_2x').
- `cms_favicon(?string $type = null)` – Returns the URL for a specific favicon type, or the full array if no type is given.
- `cms_nav_menu(string $location)` – Renders the menu assigned to a location as HTML.
- `cms_menu_items(string $location)` – Retrieves menu items as an array/Collection for custom loop rendering.
- `cms_widget_area(string $areaKey)` – Renders active widgets assigned to the given widget area.
- `bloginfo(string $show = 'name')` – Retrieves site information ('name', 'description', 'url', etc.) configured in settings.
- `cms_loop()` – Retrieves the global ThemeLoop instance.
- `have_posts()` – Checks if there are posts left in the loop.
- `the_post()` – Advances the loop to the next post.
- `wp_reset_postdata()` – Resets global post data.
- `get_post($post = null)` – Returns a Post model instance by object, ID, or current loop.
- `get_the_title($post = null)` – Retrieves the title.
- `the_title($post = null)` – Echoes the title.
- `get_the_content($post = null)` – Retrieves the content.
- `the_content($post = null)` – Echoes the content.
- `get_the_excerpt($post = null)` – Retrieves an excerpt.
- `the_excerpt($post = null)` – Echoes the excerpt.
- `get_the_permalink($post = null)` – Retrieves the URL.
- `the_permalink($post = null)` – Echoes the URL.
- `the_date(string $format = 'F j, Y', $post = null)` – Echoes the formatted publish date.
- `the_author($post = null)` – Echoes the author's name.
- `has_post_thumbnail($post = null)` – Checks for a featured image.
- `get_the_post_thumbnail_url($post = null)` – Returns the thumbnail URL.
- `the_post_thumbnail_url($post = null)` – Echoes the thumbnail URL.
- `get_menu(string $location)` – Returns rendered menu HTML.
- `is_singular($post_type = null)` – Determines if the current view is a single post of a type.
- `is_post_type_archive($post_type = null)` – Checks if the request is a post‑type archive.
- `get_post_type_archive_link(string $post_type)` – Returns archive URL.
- `the_archive_title()` – Echoes an appropriate archive title.
- `the_archive_description()` – Echoes archive description.
- `get_comments_number($post = null)` – Returns comment count.
- `the_comments($post = null)` – Echoes comment list.
- `comment_form($post = null)` – Outputs a comment form.
- `esc_html($value)`, `esc_url($value)`, `esc_attr($value)` – Escape helpers.
- `get_the_author($post = null)`, `the_author($post = null)` – Author helpers.
- `get_the_author_avatar_url($post = null)`, `the_author_avatar_url($post = null)`, `the_author_posts_link($post = null)` – Author avatar and link.
- `get_the_category($post = null)`, `the_category($separator = ', ', $post = null)` – Category helpers.
- `get_the_tags($post = null)`, `the_tags($before = '', $separator = ', ', $after = '', $post = null)` – Tag helpers.
- `apply_filters(string $tag, $value)` – Placeholder filter system.
- `the_content($post = null)`, `the_excerpt($post = null)` – Filter‑aware content/summary.
- `dynamic_sidebar(string $area)` – Renders widget area.
- Open Graph helpers: `og_title`, `og_description`, `og_image`, `og_url`, `render_og_tags`.
- Site helpers: `home_url`, `get_bloginfo`.
- Pagination: `the_posts_navigation($paginator = null)`.

These functions provide WordPress‑style templating utilities throughout LaraCMS.



## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
