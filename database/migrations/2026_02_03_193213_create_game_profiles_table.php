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
        Schema::create('game_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('display_name')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('rank')->nullable();
            $table->integer('level')->default(1);
            $table->bigInteger('total_playtime')->default(0); // Minutes
            $table->integer('achievements_count')->default(0);
            $table->json('stats')->nullable(); // Game-specific stats
            $table->json('custom_fields')->nullable(); // Flexible additional data
            $table->timestamps();
            
            $table->unique(['user_id', 'game_id']);
            $table->index('game_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_profiles');
    }
};
