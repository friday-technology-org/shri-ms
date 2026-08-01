<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CmsInstallCommand extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'Interactive terminal CMS installation wizard';

    public function handle()
    {
        $this->info('=== Welcome to Shri-ms Installer ===');

        if (file_exists(storage_path('app/.installed'))) {
            if (!$this->confirm('Shri-ms is already installed. Do you want to reinstall?', false)) {
                return 0;
            }
        }

        $siteName = $this->ask('Enter Site Name', 'Shri-ms Site');
        $email = $this->ask('Enter Admin Email Address', 'admin@example.com');
        $password = $this->secret('Enter Admin Password');

        $this->info('Running database migrations...');
        Artisan::call('migrate --force');
        Artisan::call('db:seed', ['--class' => '\\Cms\\Core\\Database\\Seeders\\CmsRolesAndPermissionsSeeder', '--force' => true]);

        $this->info('Creating admin user...');
        $user = \Cms\Core\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrator']);
        $user->assignRole($adminRole);

        update_cms_option('site_title', $siteName);

        $this->info('Generating Application Key...');
        Artisan::call('key:generate');

        // Write lock file
        File::put(storage_path('app/.installed'), '1');

        $this->info('Shri-ms installed successfully!');
        return 0;
    }
}
