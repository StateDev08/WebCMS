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
        Schema::create('moderator_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderator_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('actionable'); // Post, Thread, User, etc. (erstellt automatisch Index)
            $table->string('action'); // delete, lock, pin, warn, ban, etc.
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable(); // Original content, duration, etc.
            $table->timestamps();
            
            $table->index(['moderator_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderator_actions');
    }
};
