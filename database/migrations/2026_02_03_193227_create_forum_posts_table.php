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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('forum_threads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('forum_posts')->cascadeOnDelete(); // For replies
            
            // Dual Content System
            $table->text('content_original'); // Original format
            $table->enum('content_format', ['bbcode', 'markdown'])->default('markdown');
            $table->text('content_html'); // Pre-rendered HTML for performance
            $table->text('content_bbcode_cache')->nullable(); // Cache for converted format
            $table->text('content_markdown_cache')->nullable(); // Cache for converted format
            
            $table->boolean('is_solution')->default(false); // For Q&A threads
            $table->integer('reactions_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['thread_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
