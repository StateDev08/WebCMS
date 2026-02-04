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
        Schema::create('forum_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Spatie Media Library wird via Polymorphic Relationship verwendet (kein direkter FK)
            $table->string('filename');
            $table->string('mime_type');
            $table->bigInteger('size'); // Bytes
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_attachments');
    }
};
