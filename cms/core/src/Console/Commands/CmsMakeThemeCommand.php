<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CmsMakeThemeCommand extends Command
{
    protected $signature = 'cms:make:theme {name}';
    protected $description = 'Generate a new theme boilerplate';

    public function handle()
    {
        $name = $this->argument('name');
        $slug = strtolower($name);
        $path = base_path("cms-content/themes/{$slug}");

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
            $this->info("Theme directory created.");
        } else {
            $this->info("Theme already exists. Scaffolding missing folders...");
        }

        if (!File::isDirectory("{$path}/Controllers")) File::makeDirectory("{$path}/Controllers", 0755, true);
        if (!File::isDirectory("{$path}/Models")) File::makeDirectory("{$path}/Models", 0755, true);
        if (!File::isDirectory("{$path}/Migrations")) File::makeDirectory("{$path}/Migrations", 0755, true);
        if (!File::isDirectory("{$path}/views")) File::makeDirectory("{$path}/views", 0755, true);
        if (!File::isDirectory("{$path}/views/layouts")) File::makeDirectory("{$path}/views/layouts", 0755, true);
        if (!File::isDirectory("{$path}/assets/css")) File::makeDirectory("{$path}/assets/css", 0755, true);
        if (!File::isDirectory("{$path}/assets/js")) File::makeDirectory("{$path}/assets/js", 0755, true);

        // Create app layout if missing
        if (!File::exists("{$path}/views/layouts/app.blade.php")) {
            File::put("{$path}/views/layouts/app.blade.php", "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    <title>@yield('title', '{\$name}')</title>\n    <link rel=\"stylesheet\" href=\"{{ theme_asset('assets/css/style.css') }}\">\n</head>\n<body>\n    <header>\n        <!-- Theme Header -->\n    </header>\n\n    <main>\n        @yield('content')\n    </main>\n\n    <footer>\n        <!-- Theme Footer -->\n    </footer>\n\n    <script src=\"{{ theme_asset('assets/js/script.js') }}\"></script>\n</body>\n</html>\n");
        }

        // Create empty CSS/JS files if missing
        if (!File::exists("{$path}/assets/css/style.css")) {
            File::put("{$path}/assets/css/style.css", "/* Theme CSS for {$name} */\n");
        }
        if (!File::exists("{$path}/assets/js/script.js")) {
            File::put("{$path}/assets/js/script.js", "// Theme JS for {$name}\n");
        }

        // Create index.blade.php if missing
        if (!File::exists("{$path}/views/index.blade.php") && !File::exists("{$path}/index.blade.php")) {
            File::put("{$path}/views/index.blade.php", "@extends('theme::layouts.app')\n\n@section('content')\n    <h1>Welcome to {$name}</h1>\n@endsection\n");
        }
        
        // Create functions.php if missing
        if (!File::exists("{$path}/functions.php")) {
            File::put("{$path}/functions.php", "<?php\n\n// Require controllers here\n// require_once __DIR__ . '/Controllers/ExampleController.php';\n\n// Register theme menus, hooks, and shortcodes below\n");
        }

        $namespaceName = \Illuminate\Support\Str::studly($slug);

        // Create dummy controller
        if (!File::exists("{$path}/Controllers/ExampleController.php")) {
            File::put("{$path}/Controllers/ExampleController.php", "<?php\n\nnamespace Theme\\{$namespaceName}\\Controllers;\n\nclass ExampleController\n{\n    public function index()\n    {\n        // Return a view from the theme folder:\n        // return view('theme::index');\n        return 'Hello from ExampleController!';\n    }\n}\n");
        }

        // Create dummy model
        if (!File::exists("{$path}/Models/ExampleModel.php")) {
            File::put("{$path}/Models/ExampleModel.php", "<?php\n\nnamespace Theme\\{$namespaceName}\\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass ExampleModel extends Model\n{\n    protected \$table = 'theme_examples';\n    protected \$guarded = [];\n}\n");
        }

        // Create dummy migration
        if (!File::exists("{$path}/Migrations/2026_01_01_000000_create_theme_examples_table.php")) {
            File::put("{$path}/Migrations/2026_01_01_000000_create_theme_examples_table.php", "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up()\n    {\n        Schema::create('theme_examples', function (Blueprint \$table) {\n            \$table->id();\n            \$table->string('name');\n            \$table->timestamps();\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('theme_examples');\n    }\n};\n");
        }

        // Create theme.json if missing
        if (!File::exists("{$path}/theme.json")) {
            File::put("{$path}/theme.json", json_encode([
                'name' => $name,
                'slug' => $slug,
                'version' => '1.0.0',
                'author' => 'Developer',
            ], JSON_PRETTY_PRINT));
        }

        $this->info("Theme '{$name}' scaffolded successfully at: cms-content/themes/{$slug}");
        return 0;
    }
}
