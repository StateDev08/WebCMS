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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('tag')->nullable(); // Team tag/abbreviation
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->integer('max_members')->default(50);
            $table->boolean('is_recruiting')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Team-specific settings
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['community_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
