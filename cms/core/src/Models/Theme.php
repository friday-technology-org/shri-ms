<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\File;

class Theme
{
    public string $name;
    public string $slug;
    public string $version;
    public ?string $author;
    public ?string $description;
    public ?string $screenshot;
    public ?string $is_child_of;
    public bool $is_active = false;

    public function __construct(array $attributes = [])
    {
        $this->name = $attributes['name'] ?? 'Unknown Theme';
        $this->slug = $attributes['slug'] ?? '';
        $this->version = $attributes['version'] ?? '1.0.0';
        $this->author = $attributes['author'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->screenshot = $attributes['screenshot'] ?? null;
        $this->is_child_of = $attributes['is_child_of'] ?? null;
        $this->is_active = $attributes['is_active'] ?? false;
    }

    /**
     * Get all installed themes from the filesystem.
     * @return self[]
     */
    public static function all(): array
    {
        $path = base_path('cms-content/themes');
        if (!File::isDirectory($path)) {
            return [];
        }

        $activeThemeSlug = Option::get('active_theme', 'default');
        $themes = [];
        $dirs = File::directories($path);

        foreach ($dirs as $dir) {
            $jsonPath = $dir . '/theme.json';
            $slug = basename($dir);
            if (File::isFile($jsonPath)) {
                $meta = json_decode(File::get($jsonPath), true);
                if (is_array($meta)) {
                    $meta['slug'] = $slug;
                    $meta['is_active'] = ($slug === $activeThemeSlug);
                    $themes[] = new static($meta);
                }
            }
        }

        usort($themes, function($a, $b) {
            if ($a->is_active === $b->is_active) {
                return strcmp($a->name, $b->name);
            }
            return $a->is_active ? -1 : 1;
        });

        return $themes;
    }

    /**
     * Find a theme by slug.
     */
    public static function find(string $slug): ?self
    {
        $jsonPath = base_path("cms-content/themes/{$slug}/theme.json");
        if (!File::isFile($jsonPath)) {
            return null;
        }

        $meta = json_decode(File::get($jsonPath), true);
        if (!is_array($meta)) {
            return null;
        }

        $meta['slug'] = $slug;
        $meta['is_active'] = ($slug === Option::get('active_theme', 'default'));

        return new static($meta);
    }

    public function path(): string
    {
        return base_path('cms-content/themes/' . $this->slug);
    }

    public function screenshotUrl(): ?string
    {
        $filename = $this->screenshot;

        if (!$filename) {
            // Check for default screenshots in the theme folder
            $possibleFiles = ['screenshot.png', 'screenshot.jpg', 'screenshot.jpeg'];
            foreach ($possibleFiles as $file) {
                if (File::isFile($this->path() . '/' . $file)) {
                    $filename = $file;
                    break;
                }
            }
        }

        if (!$filename) {
            return null;
        }

        return url('themes/' . $this->slug . '/' . $filename);
    }

    public function parentTheme(): ?self
    {
        if (!$this->is_child_of) {
            return null;
        }
        return static::find($this->is_child_of);
    }
}
