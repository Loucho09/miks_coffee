<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Optimized to improve performance for Login Activity and Single Session checks.
     */
    public function up(): void
    {
        // Speed up Login Activity lookups in the profile and security monitoring
        Schema::table('login_histories', function (Blueprint $table) {
            if (Schema::hasTable('login_histories')) {
                $table->index('user_id');
            }
        });

        // Speed up Single Session middleware checks for Admin accounts
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_session_id')) {
                $table->index('last_session_id');
            }
        });

        // NEW FEATURE: High-speed Composite Indexes for Shop Operations
        // Speed up order item lookups for receipts and history
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasTable('order_items')) {
                $table->index(['order_id', 'product_id']);
            }
        });

        // Speed up dashboard rendering and customer order tracking
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasTable('orders')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        // Speed up menu filtering and active product loading
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasTable('products')) {
                $table->index(['is_active', 'category_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['last_session_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'product_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'category_id']);
        });
    }
};