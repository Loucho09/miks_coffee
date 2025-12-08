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
        // 1. CREATE USERS TABLE (I uncommented this part!)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. CREATE PASSWORD RESET TOKENS TABLE
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. CREATE SESSIONS TABLE
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 4. CREATE PRODUCTS TABLE (Kept this here since you added it)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Column for Coffee Name
            $table->string('category');      // Column for "Coffee", "Pasta", etc.
            $table->decimal('price', 8, 2);  // Column for Price (e.g., 150.00)
            $table->text('description')->nullable(); // Optional description
            $table->string('image')->nullable();     // Optional image URL
            $table->timestamps();            // Auto-creates created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('products'); // Added this to clean up properly
    }
};