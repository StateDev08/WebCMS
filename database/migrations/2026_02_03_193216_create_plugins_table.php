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
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('version');
            $table->string('author')->nullable();
            $table->string('namespace'); // PHP namespace
            $table->json('permissions')->nullable(); // Required permissions
            $table->json('config')->nullable(); // Plugin configuration
            $table->boolean('enabled')->default(false);
            $table->boolean('installed')->default(false);
            $table->timestamp('installed_at')->nullable();
            $table->foreignId('installed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['enabled', 'installed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
