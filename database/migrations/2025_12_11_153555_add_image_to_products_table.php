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
    // Only add the column if it doesn't exist yet
    if (!Schema::hasColumn('products', 'image')) {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });
    }
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
