<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->json('post_types')->nullable()->after('hierarchical');
        });

        Schema::dropIfExists('taxonomy_post_type');
    }

    public function down(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('post_types');
        });

        Schema::create('taxonomy_post_type', function (Blueprint $table) {
            $table->foreignId('taxonomy_id')->constrained('taxonomies')->onDelete('cascade');
            $table->string('post_type_slug');
            $table->primary(['taxonomy_id', 'post_type_slug']);
        });
    }
};
