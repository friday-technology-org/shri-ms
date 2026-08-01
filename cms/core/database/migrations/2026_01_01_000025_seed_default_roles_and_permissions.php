<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Automatically run the roles and permissions seeder during migration
        // This ensures that when deployed to production and `php artisan migrate` is run,
        // the default roles (like Administrator) and their permissions exist in the database.
        Artisan::call('db:seed', [
            '--class' => '\\Cms\\Core\\Database\\Seeders\\CmsRolesAndPermissionsSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not drop the roles/permissions here as they are part of the core data,
        // and would be wiped when the tables themselves are dropped.
    }
};
