<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jmloucho09@gmail.com'], 
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'usertype' => 'admin', // Matches your web.php check
                'points' => 1000,
            ]
        );
    }
}