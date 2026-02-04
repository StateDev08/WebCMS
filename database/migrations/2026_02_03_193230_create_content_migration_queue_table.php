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
        Schema::create('content_migration_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('from_format', ['bbcode', 'markdown']);
            $table->enum('to_format', ['bbcode', 'markdown']);
            $table->integer('priority')->default(10); // 1 = highest (premium), 10 = normal
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('total_items')->default(0);
            $table->integer('processed_items')->default(0);
            $table->integer('failed_items')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('error_log')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'priority', 'scheduled_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_migration_queue');
    }
};
