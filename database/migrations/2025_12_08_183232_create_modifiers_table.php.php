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
    Schema::create('modifiers', function (Blueprint $table) {
        $table->id();
        $table->string('category'); // e.g., "Sugar Level", "Ice Level", "Milk Type"
        $table->string('name');     // e.g., "0%", "Less Ice", "Oat Milk"
        $table->decimal('price', 10, 2)->default(0.00); // Extra cost (e.g., Oat Milk +$1.00)
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
