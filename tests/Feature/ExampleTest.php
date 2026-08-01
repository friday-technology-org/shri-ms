<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        @file_put_contents(storage_path('app/.installed'), '1');
        $response = $this->get('/');

        // If theme is not fully configured, it might return 200 welcome page or index
        $response->assertStatus(200);
        @unlink(storage_path('app/.installed'));
    }
}
