<?php

namespace Cms\Core\Console\Commands;

use Cms\Core\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CmsThemeMakeMigrationCommand extends Command
{
    protected $signature = 'cms:theme:make-migration {name}';
    protected $description = 'Create a new migration file for the active theme';

    public function handle()
    {
        $name = Str::snake($this->argument('name'));
        
        $activeTheme = Option::get('active_theme', 'default');
        $themePath = base_path("cms-content/themes/{$activeTheme}");
        
        if (!File::isDirectory($themePath)) {
            $this->error("The active theme directory '{$activeTheme}' does not exist.");
            return 1;
        }

        $migrationsPath = "{$themePath}/Migrations";
        if (!File::isDirectory($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$name}.php";
        $filePath = "{$migrationsPath}/{$fileName}";
        
        $className = Str::studly($name);

        $stub = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        // Schema::create('table_name', function (Blueprint \$table) {\n        //     \$table->id();\n        //     \$table->timestamps();\n        // });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        // Schema::dropIfExists('table_name');\n    }\n};\n";

        File::put($filePath, $stub);

        $this->info("Migration [{$filePath}] created successfully.");
        return 0;
    }
}
