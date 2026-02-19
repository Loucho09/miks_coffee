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
        // 1. Add Happy Hour fields to products
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'happy_hour_discount')) {
                $table->integer('happy_hour_discount')->nullable()->default(0);
                $table->time('happy_hour_start')->nullable();
                $table->time('happy_hour_end')->nullable();
            }
        });

        // 2. Add Customizations field to order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'customizations')) {
                $table->json('customizations')->nullable()->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['happy_hour_discount', 'happy_hour_start', 'happy_hour_end']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('customizations');
        });
    }
};