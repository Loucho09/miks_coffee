<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
public function run(): void
{
    \App\Models\User::updateOrCreate(
        ['email' => 'jmloucho09@gmail.com'], 
        [
            'name' => 'Admin User',
            'password' => \Illuminate\Support\Facades\Hash::make('password0909'),
            'usertype' => 'admin', 
            'points' => 1000,
        ]
    );
}
}