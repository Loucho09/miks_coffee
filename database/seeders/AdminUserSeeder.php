<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if the user exists first. If yes, update it. If no, create it.
        User::updateOrCreate(
            ['email' => 'jmloucho09@gmail.com'], // Look for this email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
    }
}