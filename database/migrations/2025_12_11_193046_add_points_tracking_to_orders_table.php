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
        Schema::table('orders', function (Blueprint $table) {
            // Check if columns exist before adding to prevent errors during refresh
            if (!Schema::hasColumn('orders', 'points_earned')) {
                $table->integer('points_earned')->default(0)->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'points_redeemed')) {
                $table->integer('points_redeemed')->default(0)->after('points_earned');
            }
            if (!Schema::hasColumn('orders', 'reward_type')) {
                $table->string('reward_type')->nullable()->after('points_redeemed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['points_earned', 'points_redeemed', 'reward_type']);
        });
    }
};