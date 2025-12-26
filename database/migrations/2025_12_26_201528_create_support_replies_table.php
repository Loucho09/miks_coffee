<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('support_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // The admin replying
            $table->text('message');
            $table->timestamps();
        });

        // Update tickets table status options
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('status')->default('pending')->change(); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('support_replies');
    }
};