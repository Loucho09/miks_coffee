<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Optimizes SQLite performance for local Windows environments by enabling WAL mode.
     */
    public function up(): void
    {
        if (config('database.default') === 'sqlite') {
            // Enable WAL mode (Write-Ahead Logging) for concurrent access
            DB::statement('PRAGMA journal_mode = WAL;');
            
            // Set synchronous to NORMAL for faster writes
            DB::statement('PRAGMA synchronous = NORMAL;');
            
            // Increase cache size to 10000 pages (approx 40MB in memory)
            DB::statement('PRAGMA cache_size = -10000;');
            
            // Move temporary storage to RAM instead of disk
            DB::statement('PRAGMA temp_store = MEMORY;');
            
            // Enable memory mapped I/O for faster data reading
            DB::statement('PRAGMA mmap_size = 30000000000;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA journal_mode = DELETE;');
            DB::statement('PRAGMA synchronous = FULL;');
        }
    }
};