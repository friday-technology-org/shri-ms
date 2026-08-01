<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('area_key'); // matches widget_areas.key by convention (like posts.post_type)
            $table->string('type'); // recent_posts | categories | tag_cloud | search | custom_html | image_banner | social_icons | nav_menu
            $table->string('title')->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['area_key', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
