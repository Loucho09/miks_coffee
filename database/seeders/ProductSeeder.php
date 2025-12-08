<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Coffee (Hot)
            ['name' => 'Americano (Hot)', 'category' => 'Coffee', 'price' => 100, 'description' => 'Rich espresso with hot water'],
            ['name' => 'Latte (Hot)', 'category' => 'Coffee', 'price' => 140, 'description' => 'Espresso with steamed milk'],
            ['name' => 'Cappuccino (Hot)', 'category' => 'Coffee', 'price' => 140, 'description' => 'Espresso with thick foam'],
            
            // Coffee (Iced)
            ['name' => 'Americano (Iced)', 'category' => 'Coffee', 'price' => 120, 'description' => 'Chilled espresso over ice'],
            ['name' => 'Spanish Latte (Iced)', 'category' => 'Coffee', 'price' => 160, 'description' => 'Sweet milky coffee with condensed milk'],
            ['name' => 'Caramel Macchiato (Iced)', 'category' => 'Coffee', 'price' => 165, 'description' => 'Espresso with vanilla and caramel drizzle'],

            // Matcha Series
            ['name' => 'Matcha Biscoff', 'category' => 'Matcha', 'price' => 180, 'description' => 'Premium matcha with Biscoff crumbs'],
            ['name' => 'Matcha Strawberry', 'category' => 'Matcha', 'price' => 150, 'description' => 'Matcha layered with strawberry'],

            // Pasta
            ['name' => 'Lasagna', 'category' => 'Pasta', 'price' => 200, 'description' => 'Layered pasta with meat, cheese, and cream sauce'],
            ['name' => 'Penne Pesto', 'category' => 'Pasta', 'price' => 199, 'description' => 'Penne pasta with pesto sauce'],

            // Cookies
            ['name' => 'Biscoff Cookie', 'category' => 'Cookies', 'price' => 65, 'description' => 'Crunchy Biscoff goodness'],
            ['name' => 'Red Velvet Cheesecake', 'category' => 'Cookies', 'price' => 55, 'description' => 'Red velvet cookie with cream cheese filling'],
        ];

        DB::table('products')->insert($products);
    }
}