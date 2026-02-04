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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->json('name'); // Translatable
            $table->string('slug');
            $table->json('description')->nullable(); // Translatable
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->json('theme_override')->nullable(); // Custom theme colors per community
            $table->boolean('is_active')->default(true);
            
            // Nested Set Columns (kalnoy/nestedset)
            $table->nestedSet();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['game_id', 'slug']);
            $table->index(['game_id', '_lft']); // For nested set queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
