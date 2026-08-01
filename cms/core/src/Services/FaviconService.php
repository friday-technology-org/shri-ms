<?php

namespace Cms\Core\Services;

use Illuminate\Http\UploadedFile;

class FaviconService
{
    protected int $maxSize = 5_242_880; // 5 MB

    protected array $allowedMimes = ['image/png', 'image/jpeg', 'image/svg+xml'];

    /**
     * Generate the favicon/site-icon set from a single uploaded source image.
     * Returns a map of asset key => public URL (a key is omitted if it
     * couldn't be generated, e.g. raster sizes when the source is an SVG —
     * GD cannot rasterize vector input, and no ImageMagick binding is
     * available in this environment).
     */
    public function generate(UploadedFile $file): array
    {
        $this->validate($file);

        $dir = 'branding/favicons';
        $this->ensureDirectory($dir);

        $mimeType = $file->getMimeType();
        $urls = [];

        if ($mimeType === 'image/svg+xml') {
            // Vector source: pass through as-is for the Safari pinned-tab icon.
            // Raster derivatives (ico/32/apple-touch/android) cannot be
            // generated from SVG with GD alone — omitted, not faked.
            $svgName = 'mask-icon.svg';
            copy($file->getRealPath(), $this->basePath($dir . '/' . $svgName));
            $urls['mask_icon'] = url('uploads/' . $dir . '/' . $svgName);

            return $urls;
        }

        $source = $this->loadImage($file->getRealPath(), $mimeType);
        if (!$source) {
            throw new \RuntimeException('Could not read the uploaded image.');
        }

        $sizes = [
            'favicon_32' => 32,
            'apple_touch' => 180,
            'android_192' => 192,
            'android_512' => 512,
        ];

        foreach ($sizes as $key => $size) {
            $filename = $key . '.png';
            $this->resizeAndSave($source, $size, $this->basePath($dir . '/' . $filename));
            $urls[$key] = url('uploads/' . $dir . '/' . $filename);
        }

        // favicon.ico: a single 32x32 PNG wrapped in a minimal ICO container
        // (PNG-in-ICO is a standard, widely-supported technique — GD has no
        // native ICO/BMP encoder to fall back on).
        $icoPath = $this->basePath($dir . '/favicon.ico');
        $this->writeIco($this->basePath($dir . '/favicon_32.png'), $icoPath);
        $urls['ico'] = url('uploads/' . $dir . '/favicon.ico');

        imagedestroy($source);

        return $urls;
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    protected function validate(UploadedFile $file): void
    {
        if ($file->getSize() > $this->maxSize) {
            throw new \RuntimeException('Icon source exceeds maximum upload size of 5 MB.');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            throw new \RuntimeException('Icon source must be a PNG, JPEG, or SVG image.');
        }
    }

    protected function loadImage(string $path, string $mimeType): \GdImage|false
    {
        return match ($mimeType) {
            'image/png' => imagecreatefrompng($path),
            'image/jpeg' => imagecreatefromjpeg($path),
            default => false,
        };
    }

    protected function resizeAndSave(\GdImage $source, int $size, string $destination): void
    {
        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $thumb = imagecreatetruecolor($size, $size);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $size, $size, $transparent);

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $size, $size, $srcW, $srcH);
        imagepng($thumb, $destination, 6);
        imagedestroy($thumb);
    }

    /**
     * Wrap a single PNG image as a minimal valid .ico file.
     */
    protected function writeIco(string $pngPath, string $icoDestination): void
    {
        $pngData = file_get_contents($pngPath);
        $pngSize = strlen($pngData);

        // ICONDIR: reserved(2)=0, type(2)=1 (icon), count(2)=1
        $header = pack('vvv', 0, 1, 1);

        // ICONDIRENTRY: width(1) height(1) colors(1) reserved(1) planes(2) bitCount(2) bytesInRes(4) imageOffset(4)
        // 32 is used as-is (0 would mean 256px, not needed here).
        $entry = pack('CCCCvvVV', 32, 32, 0, 0, 1, 32, $pngSize, 6 + 16);

        file_put_contents($icoDestination, $header . $entry . $pngData);
    }

    protected function ensureDirectory(string $relativePath): void
    {
        $dir = $this->basePath($relativePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    protected function basePath(string $relativePath = ''): string
    {
        $base = base_path('cms-content/uploads');
        return $relativePath ? $base . '/' . $relativePath : $base;
    }
}
