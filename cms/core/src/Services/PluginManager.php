<?php

namespace Cms\Core\Services;

use Cms\Core\Models\Plugin as PluginModel;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class PluginManager
{
    protected string $pluginsPath;

    public function __construct()
    {
        $this->pluginsPath = base_path('cms-content/plugins');
    }

    /**
     * Get list of all installed plugins (both active and inactive).
     */
    public function getInstalledPlugins(): array
    {
        if (!File::isDirectory($this->pluginsPath)) {
            return [];
        }

        $dirs = File::directories($this->pluginsPath);
        $plugins = [];

        foreach ($dirs as $dir) {
            $slug = basename($dir);
            $jsonPath = $dir . '/plugin.json';

            if (File::isFile($jsonPath)) {
                $meta = json_decode(File::get($jsonPath), true);
                if (is_array($meta)) {
                    $meta['slug'] = $slug;
                    // Check database status
                    $dbPlugin = PluginModel::where('slug', $slug)->first();
                    $meta['is_active'] = $dbPlugin ? $dbPlugin->is_active : false;
                    $plugins[$slug] = $meta;
                }
            }
        }

        return $plugins;
    }

    /**
     * Load all active plugins.
     */
    public function loadActivePlugins(): void
    {
        // Skip if CMS is not installed
        if (!is_cms_installed()) {
            return;
        }

        try {
            $activePlugins = PluginModel::where('is_active', true)->get();

            foreach ($activePlugins as $plugin) {
                $mainFilePath = $this->pluginsPath . '/' . $plugin->slug . '/plugin.php';
                if (File::isFile($mainFilePath)) {
                    require_once $mainFilePath;
                }
            }
        } catch (\Throwable $e) {
            // Silence exceptions during load to prevent locking the admin area
            logger()->error('Failed to load active plugins: ' . $e->getMessage());
        }
    }

    /**
     * Activate a plugin.
     */
    public function activate(string $slug): bool
    {
        $jsonPath = $this->pluginsPath . '/' . $slug . '/plugin.json';
        if (!File::isFile($jsonPath)) {
            return false;
        }

        $meta = json_decode(File::get($jsonPath), true);
        if (!is_array($meta)) {
            return false;
        }

        PluginModel::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $meta['name'] ?? $slug,
                'version' => $meta['version'] ?? '1.0.0',
                'description' => $meta['description'] ?? '',
                'author' => $meta['author'] ?? '',
                'is_active' => true,
            ]
        );

        // Trigger activation hook
        do_action('activate_plugin_' . $slug);

        return true;
    }

    /**
     * Deactivate a plugin.
     */
    public function deactivate(string $slug): bool
    {
        $plugin = PluginModel::where('slug', $slug)->first();
        if ($plugin) {
            $plugin->update(['is_active' => false]);
            // Trigger deactivation hook
            do_action('deactivate_plugin_' . $slug);
            return true;
        }
        return false;
    }

    /**
     * Install a plugin from an uploaded ZIP file.
     */
    public function installFromZip(UploadedFile $file): bool
    {
        if ($file->getClientOriginalExtension() !== 'zip') {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) === true) {
            // Find target folder inside ZIP
            $firstItem = $zip->getNameIndex(0);
            $folderName = rtrim(explode('/', $firstItem)[0], '/');

            if (empty($folderName)) {
                $zip->close();
                return false;
            }

            // Extract to plugins path
            if (!File::isDirectory($this->pluginsPath)) {
                File::makeDirectory($this->pluginsPath, 0755, true);
            }

            $zip->extractTo($this->pluginsPath);
            $zip->close();

            // Verify plugin.json exists
            $jsonPath = $this->pluginsPath . '/' . $folderName . '/plugin.json';
            if (!File::isFile($jsonPath)) {
                File::deleteDirectory($this->pluginsPath . '/' . $folderName);
                return false;
            }

            // Load meta and create DB entry as inactive
            $meta = json_decode(File::get($jsonPath), true);
            if (is_array($meta)) {
                PluginModel::updateOrCreate(
                    ['slug' => $folderName],
                    [
                        'name' => $meta['name'] ?? $folderName,
                        'version' => $meta['version'] ?? '1.0.0',
                        'description' => $meta['description'] ?? '',
                        'author' => $meta['author'] ?? '',
                        'is_active' => false,
                    ]
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Delete a plugin directory.
     */
    public function delete(string $slug): bool
    {
        $this->deactivate($slug);
        $dir = $this->pluginsPath . '/' . $slug;
        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
            PluginModel::where('slug', $slug)->delete();
            return true;
        }
        return false;
    }
}
