<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('type')->default('custom'); // custom | post | term
            $table->unsignedBigInteger('object_id')->nullable(); // posts.id or terms.id depending on type
            $table->string('label');
            $table->string('url')->nullable(); // only used when type = custom
            $table->boolean('target_blank')->default(false);
            $table->string('css_class')->nullable();
            $table->boolean('rel_nofollow')->default(false);
            $table->boolean('is_mega_menu')->default(false);
            $table->json('mega_menu_settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['menu_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
