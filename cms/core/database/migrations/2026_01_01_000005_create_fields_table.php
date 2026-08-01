<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_group_id')->constrained('field_groups')->cascadeOnDelete();
            $table->string('label');
            $table->string('name');
            $table->string('type')->default('text');
            $table->text('instructions')->nullable();
            $table->boolean('required')->default(false);
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
