<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('hierarchical')->default(false); // true for categories, false for tags
            $table->timestamps();
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained('taxonomies')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('terms')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // For term meta (e.g. category image)
            $table->timestamps();
        });

        Schema::create('term_relationships', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('terms')->onDelete('cascade');
            $table->primary(['post_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_relationships');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('taxonomies');
    }
};
