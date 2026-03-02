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
        if (Schema::hasTable('login_histories')) {
            Schema::table('login_histories', function (Blueprint $table) {
                $table->index('user_id');
            });
        }

        // Speed up Single Session middleware checks for Admin accounts
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'last_session_id')) {
                    $table->index('last_session_id');
                }
            });
        }

        // NEW FEATURE: High-speed Composite Indexes for Shop Operations
        // Speed up order item lookups for receipts and history
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index(['order_id', 'product_id']);
            });
        }

        // Speed up dashboard rendering and customer order tracking
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['user_id', 'status', 'created_at']);
            });
        }

        // Speed up menu filtering and active product loading
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['is_active', 'category_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('login_histories')) {
            Schema::table('login_histories', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['last_session_id']);
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropIndex(['order_id', 'product_id']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'status', 'created_at']);
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex(['is_active', 'category_id']);
            });
        }
    }
};