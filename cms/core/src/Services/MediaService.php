<?php

namespace Cms\Core\Services;

use Cms\Core\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Allowed MIME types for upload.
     */
    protected array $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'video/mp4', 'video/quicktime', 'video/x-msvideo',
        'audio/mpeg', 'audio/wav', 'audio/ogg',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'text/plain', 'text/csv',
    ];

    /**
     * Maximum file size in bytes (50 MB).
     */
    protected int $maxSize = 52_428_800;

    /**
     * Thumbnail size definitions [width, height, crop].
     */
    protected array $sizes = [
        'thumbnail' => [150, 150, true],
        'medium'    => [300, 300, false],
        'large'     => [1024, 1024, false],
    ];

    /**
     * Store an uploaded file and create a Media record.
     */
    public function store(UploadedFile $file, int $userId): Media
    {
        // Validate
        $this->validate($file);

        // Build storage path: YYYY/MM/
        $datePath = now()->format('Y/m');
        $extension = strtolower($file->getClientOriginalExtension());
        $uniqueName = Str::uuid()->toString() . '.' . $extension;
        $relativePath = $datePath . '/' . $uniqueName;

        // Get dimensions if image
        $width = null;
        $height = null;
        $sizes = null;
        $mimeType = $file->getMimeType();

        // Save to disk
        $this->ensureDirectory($datePath);
        $file->move($this->basePath($datePath), $uniqueName);

        // Generate thumbnails for images
        if (str_starts_with($mimeType, 'image/') && $extension !== 'svg') {
            [$width, $height] = $this->getDimensions($this->basePath($relativePath));
            $sizes = $this->generateThumbnails($this->basePath($relativePath), $datePath, $uniqueName);
        }

        return Media::create([
            'user_id'           => $userId,
            'original_filename' => $file->getClientOriginalName(),
            'filename'          => $uniqueName,
            'path'              => $relativePath,
            'disk'              => 'cms_uploads',
            'mime_type'         => $mimeType,
            'extension'         => $extension,
            'size'              => filesize($this->basePath($relativePath)),
            'width'             => $width,
            'height'            => $height,
            'sizes'             => $sizes,
        ]);
    }

    /**
     * Delete a media item: removes the file, thumbnails, and DB record.
     */
    public function delete(Media $media): void
    {
        $fullPath = $this->basePath($media->path);

        // Remove original
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Remove generated sizes
        if ($media->sizes) {
            foreach ($media->sizes as $sizePath) {
                $sizedFull = $this->basePath($sizePath);
                if (file_exists($sizedFull)) {
                    unlink($sizedFull);
                }
            }
        }

        $media->delete();
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    protected function validate(UploadedFile $file): void
    {
        if ($file->getSize() > $this->maxSize) {
            throw new \RuntimeException('File exceeds maximum upload size of 50 MB.');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            throw new \RuntimeException('File type not allowed: ' . $file->getMimeType());
        }
    }

    protected function basePath(string $relativePath = ''): string
    {
        $base = base_path('cms-content/uploads');
        return $relativePath ? $base . '/' . $relativePath : $base;
    }

    protected function ensureDirectory(string $relativeDatePath): void
    {
        $dir = $this->basePath($relativeDatePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    protected function getDimensions(string $absolutePath): array
    {
        [$width, $height] = getimagesize($absolutePath);
        return [$width, $height];
    }

    /**
     * Generate thumbnail sizes using GD.
     * Returns array of size_name => relative_path.
     */
    protected function generateThumbnails(string $sourcePath, string $datePath, string $filename): array
    {
        $sizesDir = $datePath . '/sizes';
        $this->ensureDirectory($sizesDir);

        $sourceImage = $this->loadGdImage($sourcePath);
        if (!$sourceImage) {
            return [];
        }

        $srcW = imagesx($sourceImage);
        $srcH = imagesy($sourceImage);

        $generatedSizes = [];

        foreach ($this->sizes as $sizeName => [$targetW, $targetH, $crop]) {
            $sizeFilename = pathinfo($filename, PATHINFO_FILENAME) . '-' . $sizeName . '.' . pathinfo($filename, PATHINFO_EXTENSION);
            $relativeSizePath = $sizesDir . '/' . $sizeFilename;
            $absoluteSizePath = $this->basePath($relativeSizePath);

            if ($crop) {
                // Crop to exact dimensions
                $ratio = max($targetW / $srcW, $targetH / $srcH);
                $newW = (int) round($srcW * $ratio);
                $newH = (int) round($srcH * $ratio);
                $thumb = imagecreatetruecolor($targetW, $targetH);
                $this->preserveAlpha($thumb);
                $tmp = imagecreatetruecolor($newW, $newH);
                $this->preserveAlpha($tmp);
                imagecopyresampled($tmp, $sourceImage, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
                $offsetX = (int) round(($newW - $targetW) / 2);
                $offsetY = (int) round(($newH - $targetH) / 2);
                imagecopy($thumb, $tmp, 0, 0, $offsetX, $offsetY, $targetW, $targetH);
                imagedestroy($tmp);
            } else {
                // Scale to fit within bounds preserving aspect ratio
                $ratio = min($targetW / $srcW, $targetH / $srcH, 1); // never upscale
                $newW = (int) round($srcW * $ratio);
                $newH = (int) round($srcH * $ratio);
                $thumb = imagecreatetruecolor($newW, $newH);
                $this->preserveAlpha($thumb);
                imagecopyresampled($thumb, $sourceImage, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
            }

            // Save with appropriate function
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            match ($ext) {
                'jpg', 'jpeg' => imagejpeg($thumb, $absoluteSizePath, 85),
                'png'         => imagepng($thumb, $absoluteSizePath, 6),
                'gif'         => imagegif($thumb, $absoluteSizePath),
                'webp'        => imagewebp($thumb, $absoluteSizePath, 85),
                default       => imagejpeg($thumb, $absoluteSizePath, 85),
            };

            imagedestroy($thumb);
            $generatedSizes[$sizeName] = $relativeSizePath;
        }

        imagedestroy($sourceImage);
        return $generatedSizes;
    }

    protected function loadGdImage(string $path): \GdImage|false
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($path),
            'png'         => imagecreatefrompng($path),
            'gif'         => imagecreatefromgif($path),
            'webp'        => imagecreatefromwebp($path),
            default       => false,
        };
    }

    protected function preserveAlpha(\GdImage $image): void
    {
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $transparent);
    }
}
