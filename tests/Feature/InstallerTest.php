<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InstallerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Remove .installed file if exists during test setup
        if (File::exists(storage_path('app/.installed'))) {
            File::delete(storage_path('app/.installed'));
        }
    }

    protected function tearDown(): void
    {
        if (File::exists(storage_path('app/.installed'))) {
            File::delete(storage_path('app/.installed'));
        }
        parent::tearDown();
    }

    public function test_uninstalled_site_redirects_to_installer(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/install');
    }

    public function test_installer_step1_requirements_renders(): void
    {
        $response = $this->get('/install');
        $response->assertStatus(200);
        $response->assertSee('Step 1: System Requirements');
    }

    public function test_installer_step2_database_renders(): void
    {
        $response = $this->get('/install/database');
        $response->assertStatus(200);
        $response->assertSee('Step 2: Database Configuration');
    }

    public function test_installer_step3_site_renders(): void
    {
        $response = $this->get('/install/site');
        $response->assertStatus(200);
        $response->assertSee('Step 3: Site Identity');
    }

    public function test_full_installation_process(): void
    {
        $response = $this->post('/install/process', [
            'site_title' => 'Test LaraCMS',
            'site_tagline' => 'Testing installation process',
            'admin_name' => 'Admin User',
            'admin_email' => 'admin@test.com',
            'admin_password' => 'password123',
            'admin_password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/install/finish');

        $this->assertDatabaseHas('cms_options', [
            'option_name' => 'site_title',
            'option_value' => 'Test LaraCMS',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@test.com',
        ]);

        $this->assertTrue(file_exists(storage_path('app/.installed')));
    }
}
