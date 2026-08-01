<?php

namespace Cms\Core\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use ZipArchive;

class UpgradeService
{
    protected string $updateDir;

    public function __construct()
    {
        $this->updateDir = storage_path('cms-updates');
    }

    public function checkVersion(): array
    {
        $currentVersion = '1.0.0';
        if (file_exists(base_path('cms/core/version.php'))) {
            $currentVersion = require base_path('cms/core/version.php');
        }

        // Cache the GitHub response for 1 hour (3600 seconds) to avoid rate limits
        return Cache::remember('shri_ms_update_check', 3600, function () use ($currentVersion) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'ShriMS-Update-Checker'
                ])->get('https://api.github.com/repos/friday-technology-org/shri-ms/releases/latest');

                if ($response->successful()) {
                    $release = $response->json();
                    $latestVersion = ltrim($release['tag_name'] ?? '0.0.0', 'v');
                    $downloadUrl = $release['assets'][0]['browser_download_url'] ?? $release['zipball_url'] ?? null;
                    $releaseNotes = $release['body'] ?? 'No release notes provided.';
                    
                    return [
                        'current_version' => $currentVersion,
                        'latest_version' => $latestVersion,
                        'has_update' => version_compare($latestVersion, $currentVersion, '>'),
                        'release_notes' => $releaseNotes,
                        'download_url' => $downloadUrl,
                    ];
                }
            } catch (\Exception $e) {
                // Fallback in case of exception
            }

            // Fallback if GitHub API fails
            return [
                'current_version' => $currentVersion,
                'latest_version' => $currentVersion,
                'has_update' => false,
                'release_notes' => '',
                'download_url' => null,
            ];
        });
    }

    public function performUpgrade(string $zipFilePath = null): array
    {
        if (!File::isDirectory($this->updateDir)) {
            File::makeDirectory($this->updateDir, 0755, true);
        }

        $backupZip = $this->updateDir . '/backups/pre-upgrade-' . date('Y-m-d-H-i-s') . '.zip';
        if (!File::isDirectory(dirname($backupZip))) {
            File::makeDirectory(dirname($backupZip), 0755, true);
        }

        // 1. Put system in maintenance mode
        Artisan::call('down', ['--secret' => 'cms-upgrade-secret']);

        try {
            // 2. Perform automated pre-update backup of database + cms/core/
            $zip = new ZipArchive();
            if ($zip->open($backupZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Add cms/core directory recursively
                $corePath = base_path('cms/core');
                if (File::isDirectory($corePath)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($corePath),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'cms/core/' . substr($filePath, strlen($corePath) + 1);
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }
                $zip->close();
            } else {
                throw new \Exception("Pre-upgrade backup failed. Cannot initialize backup zip.");
            }

            // 3. Extract the new core zip
            if ($zipFilePath && File::exists($zipFilePath)) {
                $extractZip = new ZipArchive();
                if ($extractZip->open($zipFilePath) === true) {
                    $extractZip->extractTo(base_path('cms/core'));
                    $extractZip->close();
                } else {
                    throw new \Exception("Failed to open the update zip file.");
                }
            } else {
                throw new \Exception("No valid update zip file provided.");
            }
            
            // 4. Run core migrations
            Artisan::call('migrate', ['--force' => true]);

            // 5. Clear Caches
            Artisan::call('optimize:clear');

            // 6. Lift maintenance mode
            Artisan::call('up');

            return [
                'success' => true,
                'message' => 'Shri-ms Core successfully upgraded.',
            ];

        } catch (\Throwable $e) {
            // 7. Auto-rollback on failure
            logger()->error('Core upgrade failed, starting auto-rollback: ' . $e->getMessage());
            
            // Restore from backup zip
            if (File::isFile($backupZip)) {
                $zip = new ZipArchive();
                if ($zip->open($backupZip) === true) {
                    $zip->extractTo(base_path());
                    $zip->close();
                }
            }

            Artisan::call('up');

            return [
                'success' => false,
                'message' => 'Upgrade failed: ' . $e->getMessage() . '. System has been restored to pre-upgrade state.',
            ];
        }
    }
}
