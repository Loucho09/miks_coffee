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
            // Unique token generated per transaction to prevent multi-scanning
            $table->string('qr_claim_token')->unique()->nullable();
            // Lock mechanism: Points can only be awarded once
            $table->boolean('points_awarded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['qr_claim_token', 'points_awarded']);
        });
    }
};