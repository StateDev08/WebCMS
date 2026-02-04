<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'seo_image_enabled')) {
                $table->boolean('seo_image_enabled')->default(true)->after('seo_image');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'seo_image_enabled')) {
                $table->boolean('seo_image_enabled')->default(true)->after('seo_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'seo_image_enabled')) {
                $table->dropColumn('seo_image_enabled');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'seo_image_enabled')) {
                $table->dropColumn('seo_image_enabled');
            }
        });
    }
};
