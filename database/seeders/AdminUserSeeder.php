<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Uses standardized loyalty_points and usertype to ensure compatibility.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'jmloucho09@gmail.com')], 
            [
                'name' => env('ADMIN_NAME', 'Admin User'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'YourExtremelySecurePassword123!')),
                'usertype' => 'admin',
                'role' => 'admin',
                'loyalty_points' => 68, // 🟢 Setting standardized 68 PTS balance
                'points' => 0,          // Deprecated legacy column
            ]
        );
    }
}