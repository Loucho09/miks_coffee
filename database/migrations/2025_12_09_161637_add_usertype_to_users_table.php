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
    // Check if the column exists first
    if (!Schema::hasColumn('users', 'usertype')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('usertype')->default('user'); // Add the column
        });
    }
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('usertype');
    });
}

    
};
