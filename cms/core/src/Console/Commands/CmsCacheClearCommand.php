<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CmsCacheClearCommand extends Command
{
    protected $signature = 'cms:cache:clear';
    protected $description = 'Purge all page, object, and asset caches';

    public function handle()
    {
        $this->info('Clearing application cache...');
        Artisan::call('cache:clear');

        $this->info('Clearing config cache...');
        Artisan::call('config:clear');

        $this->info('Clearing route cache...');
        Artisan::call('route:clear');

        $this->info('Clearing view cache...');
        Artisan::call('view:clear');

        // Clear static page cache if files exist
        \Cms\Core\Http\Middleware\PageCache::clear();

        $this->info('All Shri-ms caches cleared successfully.');
        return 0;
    }
}
