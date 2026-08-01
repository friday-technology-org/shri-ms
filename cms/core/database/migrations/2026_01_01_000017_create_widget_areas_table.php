<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_areas', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('widget_areas')->insert([
            ['key' => 'primary_sidebar', 'label' => 'Primary Sidebar', 'description' => 'Shown alongside single posts, pages, and archives.', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'header_top_bar', 'label' => 'Header Top Bar', 'description' => 'Thin bar above the main site header.', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'footer_col_1', 'label' => 'Footer Column 1', 'description' => null, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'footer_col_2', 'label' => 'Footer Column 2', 'description' => null, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'footer_col_3', 'label' => 'Footer Column 3', 'description' => null, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'footer_col_4', 'label' => 'Footer Column 4', 'description' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_areas');
    }
};
