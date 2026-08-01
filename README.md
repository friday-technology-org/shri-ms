

# Shri-ms

Shri-ms is a lightweight, modular content management system built on Laravel, offering a WordPress‑style theming API and plugin architecture. It provides an intuitive way to manage posts, custom post types, taxonomies, and media while leveraging Laravel's powerful features.

## Table of Contents
- [About Shri-ms](#about-shri-ms)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Documentation](#documentation)
- [Developer Features](#developer-features)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [License](#license)

## About Shri-ms

Shri-ms merges the flexibility and developer experience of Laravel with the ease-of-use and extensibility of traditional CMS platforms like WordPress. 

## Prerequisites

- PHP 8.2+
- Composer
- Docker (or local PHP environment)
- MySQL / SQLite

## Installation

```bash
git clone https://github.com/friday-technology-org/shri-ms.git
cd shri-ms
docker-compose up -d   # or php artisan serve
composer install
php artisan key:generate
php artisan migrate --seed
```

## Configuration

Edit your `.env` file to set database credentials, app URL, and other environment variables.

## Running the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see your local instance of Shri-ms.

## Documentation

Shri-ms supports both extensive theming and plugin capabilities. We have detailed handbooks for both:

- **[Theme Handbook](docs/theme-handbook.md):** Learn how to create custom themes, use our Blade directives, and leverage WordPress-style helper functions (like `have_posts()`, `the_title()`, etc.).
- **[Plugin Handbook](docs/plugin-handbook.md):** Discover the plugin architecture, learn how to scaffold new plugins, and register custom routes, widgets, and APIs.

*Other Resources:*
- Sample projects in the `examples/` directory
- Community tutorials and video guides

## Developer Features

### Helper Functions
Shri-ms includes extensive helpers to accelerate development:
- **SEO & Meta:** `get_page_seo(string $slug)`
- **Theme & UI:** `cms_logo()`, `cms_favicon()`, `cms_nav_menu()`, `cms_widget_area()`
- **The Loop:** `have_posts()`, `the_post()`, `get_the_title()`, `the_content()`, `the_excerpt()`
- **Routing & Structure:** `is_singular()`, `is_post_type_archive()`, `the_archive_title()`
- **Taxonomies:** `the_category()`, `the_tags()`

*(For a comprehensive list of helper functions, see the Theme Handbook).*

## Roadmap

- **v1.1** – Widget area API, dynamic sidebars.
- **v1.2** – Full Gutenberg‑style block editor integration.
- **v1.3** – Multisite support, REST API.
- **v2.0** – Headless CMS mode, GraphQL endpoint.

## Contributing

Thank you for considering contributing to Shri-ms! Please see the `CONTRIBUTING.md` for guidelines. 
Follow PSR-12 coding standards, write tests, and run `php artisan test` before submitting pull requests.

## Security Vulnerabilities

If you discover a security vulnerability within Shri-ms, please open an issue or contact the maintainers directly. All security vulnerabilities will be promptly addressed.

## License

Shri-ms is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
