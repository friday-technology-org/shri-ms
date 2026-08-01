<?php

namespace Cms\Core\Console\Commands;

use Illuminate\Console\Command;
use Cms\Core\Models\User;
use Illuminate\Support\Facades\Hash;

class CmsUserCreateCommand extends Command
{
    protected $signature = 'cms:user:create {name} {email} {password} {--role=Administrator}';
    protected $description = 'Create admin users via command line';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $roleName = $this->option('role');

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists.");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Assign Role
        try {
            $user->assignRole($roleName);
        } catch (\Throwable $e) {
            $this->warn("Role '{$roleName}' could not be assigned automatically: " . $e->getMessage());
        }

        $this->info("User '{$name}' created successfully as {$roleName}.");
        return 0;
    }
}
