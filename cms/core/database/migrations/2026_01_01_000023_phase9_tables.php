<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create cms_sites table
        Schema::create('cms_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable()->unique();
            $table->string('path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default site
        DB::table('cms_sites')->insert([
            'id' => 1,
            'name' => 'Default Site',
            'domain' => null,
            'path' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create api_tokens table
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->json('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 3. Create cms_webhooks table
        Schema::create('cms_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->json('events');
            $table->string('secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Update users table with locale preference
        if (!Schema::hasColumn('users', 'locale')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('locale', 10)->default('en');
            });
        }

        // 5. Add site_id, lang, translation_of_id to content tables
        $tablesToUpdate = [
            'posts' => true, // includes site_id, lang, translation_of_id
            'taxonomies' => true,
            'menus' => true,
            'cms_options' => false, // only site_id
            'media' => false,
            'comments' => false,
            'widget_areas' => false,
            'widgets' => false,
        ];

        foreach ($tablesToUpdate as $tbl => $includeTranslations) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl, $includeTranslations) {
                    if (!Schema::hasColumn($tbl, 'site_id')) {
                        $table->unsignedBigInteger('site_id')->nullable()->default(1);
                    }
                    if ($includeTranslations) {
                        if (!Schema::hasColumn($tbl, 'lang')) {
                            $table->string('lang', 10)->default('en');
                        }
                        if (!Schema::hasColumn($tbl, 'translation_of_id')) {
                            $table->unsignedBigInteger('translation_of_id')->nullable();
                        }
                    }
                });

                // Populate default site_id
                DB::table($tbl)->update(['site_id' => 1]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_webhooks');
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('cms_sites');

        if (Schema::hasColumn('users', 'locale')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('locale');
            });
        }

        $tablesToUpdate = ['posts', 'taxonomies', 'menus', 'cms_options', 'media', 'comments', 'widget_areas', 'widgets'];
        foreach ($tablesToUpdate as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (Schema::hasColumn($tbl, 'site_id')) {
                        $table->dropColumn('site_id');
                    }
                    if (Schema::hasColumn($tbl, 'lang')) {
                        $table->dropColumn('lang');
                    }
                    if (Schema::hasColumn($tbl, 'translation_of_id')) {
                        $table->dropColumn('translation_of_id');
                    }
                });
            }
        }
    }
};
