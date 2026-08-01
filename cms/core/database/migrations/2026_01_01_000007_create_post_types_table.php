<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. portfolio
            $table->string('singular_label'); // e.g. Portfolio Item
            $table->string('plural_label'); // e.g. Portfolios
            $table->string('description')->nullable();
            $table->string('icon')->nullable(); // e.g. icon-file.svg
            $table->boolean('is_hierarchical')->default(false);
            $table->boolean('has_archive')->default(true);
            $table->json('supports')->nullable(); // e.g. ["title", "editor", "thumbnail"]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_types');
    }
};
