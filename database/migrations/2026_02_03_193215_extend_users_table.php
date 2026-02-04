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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('remember_token');
            $table->string('locale')->default('de')->after('is_premium');
            $table->string('theme')->default('default')->after('locale');
            $table->json('theme_config')->nullable()->after('theme'); // User custom colors
            
            // Stripe/Cashier Integration
            $table->string('stripe_id')->nullable()->index()->after('theme_config');
            $table->string('pm_type')->nullable()->after('stripe_id');
            $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            $table->timestamp('trial_ends_at')->nullable()->after('pm_last_four');
            
            // Stats
            $table->integer('posts_count')->default(0)->after('trial_ends_at');
            $table->integer('threads_count')->default(0)->after('posts_count');
            $table->timestamp('last_activity_at')->nullable()->after('threads_count');
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_premium', 'locale', 'theme', 'theme_config',
                'stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at',
                'posts_count', 'threads_count', 'last_activity_at', 'deleted_at'
            ]);
        });
    }
};
