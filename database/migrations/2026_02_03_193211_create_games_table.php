<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Translatable: {"de": "Name", "en": "Name"}
            $table->string('slug')->unique();
            $table->json('description')->nullable(); // Translatable
            $table->string('icon')->nullable(); // URL to game icon
            $table->string('banner')->nullable(); // URL to game banner
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('meta')->nullable(); // Additional game metadata
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
