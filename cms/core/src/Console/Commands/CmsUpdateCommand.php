<?php

namespace Cms\Core\Console\Commands;

use Cms\Core\Services\UpgradeService;
use Illuminate\Console\Command;

class CmsUpdateCommand extends Command
{
    protected $signature = 'cms:update';
    protected $description = 'Command-line core upgrade runner';

    public function handle(UpgradeService $upgradeService)
    {
        $this->info('Checking for updates...');
        $info = $upgradeService->checkVersion();

        if (!$info['has_update']) {
            $this->info('LaraCMS is already up to date.');
            return 0;
        }

        $this->info("New version available: {$info['latest_version']}");
        if (!$this->confirm('Do you want to run the upgrade now?', true)) {
            return 0;
        }

        $this->info('Upgrading LaraCMS Core...');
        $result = $upgradeService->performUpgrade();

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        return 0;
    }
}
