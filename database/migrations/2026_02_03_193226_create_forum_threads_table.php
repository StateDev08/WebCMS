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
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('forum_categories')->cascadeOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('community_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->boolean('is_sticky')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('pinned_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('posts_count')->default(0);
            $table->unsignedBigInteger('last_post_id')->nullable(); // FK wird später hinzugefügt
            $table->timestamp('last_post_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category_id', 'is_sticky', 'last_post_at']);
            $table->index(['community_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_threads');
    }
};
