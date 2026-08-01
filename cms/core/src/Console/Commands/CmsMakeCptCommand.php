<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Cms\Core\Models\PostType;

class CmsMakeCptCommand extends Command
{
    protected $signature = 'cms:make:cpt {name} {--plural=}';
    protected $description = 'Generate a new Custom Post Type and register it in LaraCMS';

    public function handle()
    {
        $name = strtolower($this->argument('name'));
        $plural = $this->option('plural') ?: ucfirst($name) . 's';

        if (PostType::where('name', $name)->exists()) {
            $this->error("Custom Post Type '{$name}' already registered.");
            return 1;
        }

        PostType::create([
            'name' => $name,
            'singular_label' => ucfirst($name),
            'plural_label' => $plural,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'icon-file.svg',
        ]);

        $this->info("Custom Post Type '{$name}' registered successfully.");
        return 0;
    }
}
