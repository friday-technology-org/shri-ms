<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomy_post_type', function (Blueprint $table) {
            $table->foreignId('taxonomy_id')->constrained('taxonomies')->onDelete('cascade');
            $table->string('post_type_slug');
            
            // Note: post_type_slug may refer to static post types like 'post' or 'page' 
            // which don't exist in the post_types table, so we don't enforce a foreign key to post_types.
            $table->primary(['taxonomy_id', 'post_type_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomy_post_type');
    }
};
