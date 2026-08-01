<?php

namespace Cms\Core\Console\Commands;

use Cms\Core\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsThemeMakeModelCommand extends Command
{
    protected $signature = 'cms:theme:make-model {name}';
    protected $description = 'Create a new Eloquent model class for the active theme';

    public function handle()
    {
        $name = $this->argument('name');
        
        $activeTheme = Option::get('active_theme', 'default');
        $themePath = base_path("cms-content/themes/{$activeTheme}");
        
        if (!File::isDirectory($themePath)) {
            $this->error("The active theme directory '{$activeTheme}' does not exist.");
            return 1;
        }

        $modelsPath = "{$themePath}/Models";
        if (!File::isDirectory($modelsPath)) {
            File::makeDirectory($modelsPath, 0755, true);
        }

        $name = str_replace('/', '\\', $name);
        $parts = explode('\\', $name);
        $className = array_pop($parts);
        
        $subNamespace = count($parts) > 0 ? '\\' . implode('\\', $parts) : '';
        $subPath = count($parts) > 0 ? '/' . implode('/', $parts) : '';
        
        if ($subPath && !File::isDirectory($modelsPath . $subPath)) {
            File::makeDirectory($modelsPath . $subPath, 0755, true);
        }

        $filePath = "{$modelsPath}{$subPath}/{$className}.php";
        
        if (File::exists($filePath)) {
            $this->error("Model '{$name}' already exists in theme '{$activeTheme}'.");
            return 1;
        }

        $namespaceName = Str::studly($activeTheme);
        $fullNamespace = "Theme\\{$namespaceName}\\Models{$subNamespace}";

        $stub = "<?php\n\nnamespace {$fullNamespace};\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$className} extends Model\n{\n    //\n}\n";

        File::put($filePath, $stub);

        $this->info("Model [{$filePath}] created successfully.");
        return 0;
    }
}
