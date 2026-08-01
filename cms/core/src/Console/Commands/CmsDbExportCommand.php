<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Cms\Core\Services\BackupService;

class CmsDbExportCommand extends Command
{
    protected $signature = 'cms:db:export';
    protected $description = 'Export CMS content database';

    public function handle(BackupService $backupService)
    {
        $this->info('Starting database export...');
        $filename = $backupService->createBackup();

        if ($filename) {
            $this->info("Database exported and zipped successfully as: {$filename}");
        } else {
            $this->error('Failed to export database.');
        }

        return 0;
    }
}
