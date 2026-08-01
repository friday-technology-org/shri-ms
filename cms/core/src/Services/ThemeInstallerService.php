<?php

namespace Cms\Core\Services;

use Cms\Core\Models\Theme;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ZipArchive;

class ThemeInstallerService
{
    protected int $maxSize = 20_971_520; // 20 MB

    /**
     * Validate, extract, and register a theme from an uploaded .zip file.
     * The zip is extracted to a scratch directory first so a bad/invalid
     * package never touches the live cms-content/themes/ tree.
     */
    public function install(UploadedFile $zip): Theme
    {
        $this->validateUpload($zip);

        $scratchDir = storage_path('app/tmp/theme-extract-' . Str::uuid());
        mkdir($scratchDir, 0755, true);

        try {
            $this->extract($zip, $scratchDir);
            $themeRoot = $this->resolveThemeRoot($scratchDir);
            $manifest = $this->validateThemeRoot($themeRoot);

            $slug = $manifest['slug'];

            if (is_dir($this->themesBasePath($slug))) {
                throw new \RuntimeException("A theme with slug \"{$slug}\" is already installed.");
            }

            $this->ensureThemesBaseDirectory();
            rename($themeRoot, $this->themesBasePath($slug));

            return Theme::find($slug);
        } finally {
            $this->deleteDirectory($scratchDir);
        }
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    protected function validateUpload(UploadedFile $zip): void
    {
        if ($zip->getSize() > $this->maxSize) {
            throw new \RuntimeException('Theme package exceeds maximum upload size of 20 MB.');
        }

        $extension = strtolower($zip->getClientOriginalExtension());
        if ($extension !== 'zip') {
            throw new \RuntimeException('Theme package must be a .zip file.');
        }
    }

    protected function extract(UploadedFile $zip, string $destination): void
    {
        $archive = new ZipArchive();

        if ($archive->open($zip->getRealPath()) !== true) {
            throw new \RuntimeException('Uploaded file is not a valid zip archive.');
        }

        $archive->extractTo($destination);
        $archive->close();
    }

    /**
     * If the zip's contents are wrapped in a single subdirectory
     * (common when downloading a zip from GitHub etc), descend into it.
     */
    protected function resolveThemeRoot(string $extractedPath): string
    {
        $entries = array_values(array_diff(scandir($extractedPath) ?: [], ['.', '..']));

        if (count($entries) === 1 && is_dir($extractedPath . '/' . $entries[0])) {
            return $extractedPath . '/' . $entries[0];
        }

        return $extractedPath;
    }

    /**
     * Validate the theme package has a manifest + entry template, and return the decoded manifest.
     */
    protected function validateThemeRoot(string $themeRoot): array
    {
        $manifestPath = $themeRoot . '/theme.json';

        if (!is_file($manifestPath)) {
            throw new \RuntimeException('Theme package is missing a theme.json manifest.');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!is_array($manifest)) {
            throw new \RuntimeException('theme.json is not valid JSON.');
        }

        $missing = array_diff(['name', 'slug', 'version'], array_keys(array_filter($manifest, fn ($v) => $v !== null && $v !== '')));
        if (!empty($missing)) {
            throw new \RuntimeException('theme.json is missing required field(s): ' . implode(', ', $missing));
        }

        if (!preg_match('/^[a-z0-9\-]+$/', $manifest['slug'])) {
            throw new \RuntimeException('Theme slug must contain only lowercase letters, numbers, and hyphens.');
        }

        if (!is_file($themeRoot . '/index.blade.php')) {
            throw new \RuntimeException('Theme package is missing an index.blade.php template.');
        }

        return $manifest;
    }

    protected function ensureThemesBaseDirectory(): void
    {
        $dir = base_path('cms-content/themes');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    protected function themesBasePath(string $slug = ''): string
    {
        $base = base_path('cms-content/themes');
        return $slug ? $base . '/' . $slug : $base;
    }

    protected function deleteDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $items = array_diff(scandir($path) ?: [], ['.', '..']);
        foreach ($items as $item) {
            $itemPath = $path . '/' . $item;
            is_dir($itemPath) ? $this->deleteDirectory($itemPath) : unlink($itemPath);
        }

        rmdir($path);
    }
}
