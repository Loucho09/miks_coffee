<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Meals', 'is_active' => true],
            ['name' => 'Frappes', 'is_active' => true],
            ['name' => 'Coffee', 'is_active' => true],
            ['name' => 'Waffles', 'is_active' => true],
            ['name' => 'Sandwiches', 'is_active' => true],
            ['name' => 'Pasta', 'is_active' => true],
            ['name' => 'Fruit Tea', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}