<?php

namespace Cms\Core\Console\Commands;

use Cms\Core\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsThemeMakeControllerCommand extends Command
{
    protected $signature = 'cms:theme:make-controller {name}';
    protected $description = 'Create a new controller class for the active theme';

    public function handle()
    {
        $name = $this->argument('name');
        
        // Resolve active theme
        $activeTheme = Option::get('active_theme', 'default');
        $themePath = base_path("cms-content/themes/{$activeTheme}");
        
        if (!File::isDirectory($themePath)) {
            $this->error("The active theme directory '{$activeTheme}' does not exist.");
            return 1;
        }

        $controllersPath = "{$themePath}/Controllers";
        if (!File::isDirectory($controllersPath)) {
            File::makeDirectory($controllersPath, 0755, true);
        }

        // Determine namespace and file path
        $name = str_replace('/', '\\', $name);
        $parts = explode('\\', $name);
        $className = array_pop($parts);
        
        $subNamespace = count($parts) > 0 ? '\\' . implode('\\', $parts) : '';
        $subPath = count($parts) > 0 ? '/' . implode('/', $parts) : '';
        
        if ($subPath && !File::isDirectory($controllersPath . $subPath)) {
            File::makeDirectory($controllersPath . $subPath, 0755, true);
        }

        $filePath = "{$controllersPath}{$subPath}/{$className}.php";
        
        if (File::exists($filePath)) {
            $this->error("Controller '{$name}' already exists in theme '{$activeTheme}'.");
            return 1;
        }

        $namespaceName = Str::studly($activeTheme);
        $fullNamespace = "Theme\\{$namespaceName}\\Controllers{$subNamespace}";

        $stub = "<?php\n\nnamespace {$fullNamespace};\n\nuse Illuminate\Routing\Controller;\nuse Illuminate\Http\Request;\n\nclass {$className} extends Controller\n{\n    //\n}\n";

        File::put($filePath, $stub);

        $this->info("Controller [{$filePath}] created successfully.");
        return 0;
    }
}
