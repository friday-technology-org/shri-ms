<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class InstallController extends Controller
{
    /**
     * Step 1: Requirements and directory permissions checker.
     */
    public function step1_requirements()
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version >= 8.2',
                'pass' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'current' => PHP_VERSION,
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'pass' => extension_loaded('pdo'),
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'pass' => extension_loaded('mbstring'),
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'pass' => extension_loaded('openssl'),
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'pass' => extension_loaded('tokenizer'),
            ],
            'xml' => [
                'name' => 'XML Extension',
                'pass' => extension_loaded('xml'),
            ],
            'ctype' => [
                'name' => 'Ctype Extension',
                'pass' => extension_loaded('ctype'),
            ],
            'json' => [
                'name' => 'JSON Extension',
                'pass' => extension_loaded('json'),
            ],
            'bcmath' => [
                'name' => 'BCMath Extension',
                'pass' => extension_loaded('bcmath'),
            ],
            'fileinfo' => [
                'name' => 'Fileinfo Extension',
                'pass' => extension_loaded('fileinfo'),
            ],
            'gd' => [
                'name' => 'GD / Imagick Extension',
                'pass' => extension_loaded('gd') || extension_loaded('imagick'),
            ],
        ];

        $permissions = [
            'storage' => [
                'name' => 'storage/',
                'pass' => is_writable(storage_path()),
            ],
            'bootstrap_cache' => [
                'name' => 'bootstrap/cache/',
                'pass' => is_writable(base_path('bootstrap/cache')),
            ],
            'env' => [
                'name' => '.env file / workspace root',
                'pass' => file_exists(base_path('.env')) ? is_writable(base_path('.env')) : is_writable(base_path()),
            ],
        ];

        $allPassed = !in_array(false, array_column($requirements, 'pass')) && !in_array(false, array_column($permissions, 'pass'));

        return view('cms-core::install.step1-requirements', compact('requirements', 'permissions', 'allPassed'));
    }

    /**
     * Step 2: Database Configuration Form.
     */
    public function step2_database()
    {
        $dbConfig = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'shrims'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ];

        return view('cms-core::install.step2-database', compact('dbConfig'));
    }

    /**
     * AJAX Test Database Connection.
     */
    public function testDatabaseConnection(Request $request): JsonResponse
    {
        $driver = $request->input('driver', 'mysql');
        $host = $request->input('host', '127.0.0.1');
        $port = $request->input('port', '3306');
        $database = $request->input('database', 'shrims');
        $username = $request->input('username', 'root');
        $password = $request->input('password', '');

        try {
            if ($driver === 'sqlite') {
                if (!file_exists($database) && $database !== ':memory:') {
                    return response()->json(['success' => false, 'message' => "SQLite database file does not exist at {$database}"]);
                }
                $dsn = "sqlite:{$database}";
                new PDO($dsn);
            } else {
                $dsn = "{$driver}:host={$host};port={$port};dbname={$database}";
                new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            }

            return response()->json(['success' => true, 'message' => 'Database connection successful!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Save Database Configuration into .env.
     */
    public function saveDatabase(Request $request)
    {
        $request->validate([
            'driver' => 'required|string',
            'host' => 'required_unless:driver,sqlite',
            'port' => 'required_unless:driver,sqlite',
            'database' => 'required|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $this->updateEnv([
            'DB_CONNECTION' => $request->input('driver'),
            'DB_HOST' => $request->input('host', '127.0.0.1'),
            'DB_PORT' => $request->input('port', '3306'),
            'DB_DATABASE' => $request->input('database'),
            'DB_USERNAME' => $request->input('username', ''),
            'DB_PASSWORD' => $request->input('password', ''),
        ]);

        return redirect()->route('install.step3');
    }

    /**
     * Step 3: Site Setup & Super Admin Form.
     */
    public function step3_site()
    {
        return view('cms-core::install.step3-site');
    }

    /**
     * Process Full Installation (Migrate, Seed, Admin user, Lock file).
     */
    public function processInstall(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // 1. Run migrations
            Artisan::call('migrate', ['--force' => true]);
            
            // 1a. Run seeders
            Artisan::call('db:seed', ['--class' => '\\Cms\\Core\\Database\\Seeders\\CmsRolesAndPermissionsSeeder', '--force' => true]);

            // 1b. Symlink cms-content/{uploads,themes} into public/ so their files are web-reachable
            if (!file_exists(public_path('uploads'))) {
                symlink(base_path('cms-content/uploads'), public_path('uploads'));
            }
            if (!file_exists(public_path('themes'))) {
                symlink(base_path('cms-content/themes'), public_path('themes'));
            }

            // 1c. Register the bundled default theme as active
            update_cms_option('active_theme', 'default');

            // 2. Save site settings in cms_options
            update_cms_option('site_title', $request->input('site_title'));
            update_cms_option('site_tagline', $request->input('site_tagline', 'Just another Shri-ms site'));
            update_cms_option('site_url', config('app.url', 'http://localhost:8000'));
            update_cms_option('default_role', 'subscriber');
            update_cms_option('default_category', 'Uncategorized');

            // 3. Create Super Admin user
            $user = User::updateOrCreate(
                ['email' => $request->input('admin_email')],
                [
                    'name' => $request->input('admin_name'),
                    'password' => Hash::make($request->input('admin_password')),
                    'email_verified_at' => now(),
                ]
            );

            // Create Administrator role and assign it to the user
            $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrator']);
            $user->assignRole($adminRole);

            // 4. Generate App Key if not already generated
            if (empty(env('APP_KEY'))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // 5. Create .installed lock file
            file_put_contents(storage_path('app/.installed'), json_encode([
                'installed_at' => now()->toDateTimeString(),
                'version' => '1.0.0',
            ]));

            return redirect()->route('install.finish');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Installation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Step 4: Installation Finish.
     */
    public function step4_finish()
    {
        return view('cms-core::install.step4-finish');
    }

    /**
     * Helper to update .env key-value pairs.
     */
    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envPath);
            } else {
                file_put_contents($envPath, '');
            }
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $formattedValue = (str_contains($value, ' ') || str_contains($value, '#')) ? '"' . $value . '"' : $value;
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$formattedValue}", $envContent);
            } else {
                $envContent .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
