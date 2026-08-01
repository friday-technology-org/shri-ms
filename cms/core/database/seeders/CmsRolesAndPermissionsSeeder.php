<?php

namespace Cms\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CmsRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create core permissions
        $permissions = [
            'manage_options',
            'edit_users',
            'create_users',
            'delete_users',
            'edit_roles',
            
            'edit_posts',
            'edit_others_posts',
            'publish_posts',
            'read_private_posts',
            'delete_posts',
            'delete_others_posts',
            
            'edit_pages',
            'edit_others_pages',
            'publish_pages',
            'read_private_pages',
            'delete_pages',
            'delete_others_pages',
            
            'upload_files',
            'manage_categories',
            'manage_themes',
            'manage_plugins'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions

        // 1. Administrator
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. Editor
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $editorRole->givePermissionTo([
            'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_others_posts',
            'edit_pages', 'edit_others_pages', 'publish_pages', 'read_private_pages', 'delete_pages', 'delete_others_pages',
            'upload_files', 'manage_categories'
        ]);

        // 3. Author
        $authorRole = Role::firstOrCreate(['name' => 'Author']);
        $authorRole->givePermissionTo([
            'edit_posts', 'publish_posts', 'delete_posts', 'upload_files'
        ]);

        // 4. Contributor
        $contributorRole = Role::firstOrCreate(['name' => 'Contributor']);
        $contributorRole->givePermissionTo([
            'edit_posts', 'delete_posts'
        ]);

        // 5. Subscriber
        Role::firstOrCreate(['name' => 'Subscriber']);
    }
}
