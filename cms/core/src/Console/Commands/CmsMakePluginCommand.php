<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CmsMakePluginCommand extends Command
{
    protected $signature = 'cms:make:plugin {name}';
    protected $description = 'Generate a new plugin scaffolding';

    public function handle()
    {
        $name = $this->argument('name');
        $slug = strtolower($name);
        $path = base_path("cms-content/plugins/{$slug}");

        if (File::isDirectory($path)) {
            $this->error("Plugin '{$name}' already exists.");
            return 1;
        }

        File::makeDirectory($path, 0755, true);
        File::makeDirectory("{$path}/src", 0755, true);

        // Create main plugin file
        File::put("{$path}/plugin.php", "<?php\n\n// Main plugin file for {$name}\n");
        // Create metadata file
        File::put("{$path}/plugin.json", json_encode([
            'name' => $name,
            'slug' => $slug,
            'version' => '1.0.0',
            'description' => 'A custom plugin for LaraCMS.',
        ], JSON_PRETTY_PRINT));

        $this->info("Plugin '{$name}' generated successfully at: cms-content/plugins/{$slug}");
        return 0;
    }
}
