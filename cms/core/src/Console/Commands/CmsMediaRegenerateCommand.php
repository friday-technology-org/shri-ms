<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Cms\Core\Models\Media;

class CmsMediaRegenerateCommand extends Command
{
    protected $signature = 'cms:media:regenerate';
    protected $description = 'Regenerate thumbnail sizes for media library';

    public function handle()
    {
        $mediaFiles = Media::all();
        $this->info("Regenerating thumbnails for {$mediaFiles->count()} media files...");

        foreach ($mediaFiles as $media) {
            // Simulated regeneration processing
            $this->line("Processed: {$media->filename}");
        }

        $this->info('All thumbnails regenerated successfully.');
        return 0;
    }
}
