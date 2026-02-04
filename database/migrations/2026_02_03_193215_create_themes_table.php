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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('community_id')->nullable()->constrained()->nullOnDelete(); // null = global theme
            $table->json('config'); // Colors, fonts, spacing, etc.
            $table->text('custom_css')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->string('version')->default('1.0.0');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['is_active', 'community_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
