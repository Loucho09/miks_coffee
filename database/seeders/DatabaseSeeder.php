<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Admin
        $this->call(AdminUserSeeder::class);

        // 2. Setup Test Customer
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Test Customer', 'password' => Hash::make('password'), 'role' => 'user', 'points' => 50]
        );

        // 3. Menu Data (Organized by Categories)
        $menu = [
            'Rice Meals' => [
                ['name' => 'Chicken Teriyaki', 'price' => 119, 'desc' => 'Savory chicken glazed in teriyaki sauce.'],
                ['name' => 'Katsudon', 'price' => 139, 'desc' => 'Breaded pork cutlet and egg.'],
                ['name' => 'Chicken Pastil', 'price' => 99, 'desc' => 'Shredded chicken topped over steamed rice.'],
            ],
            'Best Flavored Chicken' => [
                ['name' => 'Chicken Fingers with Rice', 'price' => 89, 'desc' => 'Crispy chicken fingers served with rice.'],
                ['name' => '2pcs Chicken with Rice', 'price' => 129, 'desc' => 'Two pieces of flavored fried chicken.'],
                ['name' => '6pcs Chicken', 'price' => 269, 'desc' => 'Six pieces of flavored chicken.'],
            ],
            'Frappe' => [
                ['name' => 'Avocado Frappe', 'price' => 99, 'desc' => 'Creamy avocado blended drink.'],
                ['name' => 'Black Forest Frappe', 'price' => 99, 'desc' => 'Chocolate and cherry flavor blend.'],
                ['name' => 'Buko Pandan Frappe', 'price' => 99, 'desc' => 'Coconut and pandan flavor.'],
            ],
            'Coffee Based' => [
                ['name' => 'Americano', 'price' => 79, 'desc' => 'Espresso with hot water.'],
                ['name' => 'Biscoffee', 'price' => 129, 'desc' => 'Coffee infused with Biscoff flavor.'],
                ['name' => 'Cappuccino', 'price' => 89, 'desc' => 'Espresso with steamed milk foam.'],
            ],
            'Waffles' => [
                ['name' => 'Oreo Waffle', 'price' => 89, 'desc' => 'Oreo, crushed oreo, and chocolate syrup.'],
                ['name' => 'Nutella Waffle', 'price' => 99, 'desc' => 'Nutella and sliced almond.'],
                ['name' => 'Biscoff Waffle', 'price' => 129, 'desc' => 'Biscoff syrup and crushed biscoff.'],
            ],
            'Pasta & Sandwiches' => [
                ['name' => 'Ham and Cheese Sandwich', 'price' => 125, 'desc' => 'Classic cafe sandwich.'],
                ['name' => 'Overload Fries', 'price' => 79, 'desc' => 'Fries, cheese sauce and beef.'],
                ['name' => 'Penne Carbonara', 'price' => 149, 'desc' => 'Pasta served with garlic bread.'],
            ],
            // 🟢 ADDED: Fruit Tea's Category
            'Fruit Tea\'s' => [
                ['name' => 'Blueberry Fruit Tea', 'price' => 89, 'desc' => 'Refreshing tea infused with sweet blueberry flavor.'],
                ['name' => 'Green Apple Fruit Tea', 'price' => 89, 'desc' => 'Crisp and tart green apple flavored tea.'],
                ['name' => 'Lychee Fruit Tea', 'price' => 89, 'desc' => 'Exotic and sweet lychee infused refreshing tea.'],
            ],
        ];

        // 4. Sync to DB
      foreach ($menu as $categoryName => $products) {
            $category = Category::updateOrCreate(['name' => $categoryName], ['slug' => Str::slug($categoryName)]);

            foreach ($products as $item) {
                $product = Product::updateOrCreate(
                    ['name' => $item['name']],
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($item['name']),
                        'price' => $item['price'],
                        'description' => $item['desc'],
                        'stock_quantity' => 50,
                        'is_active' => true,
                    ]
                );

                // 🟢 ONLY add sizes to Coffee and Fruit Tea categories
                if ($categoryName === 'Coffee Based' || $categoryName === 'Fruit Tea\'s') {
                    $product->sizes()->updateOrCreate(['size' => '12oz'], ['price' => 39.00]);
                    $product->sizes()->updateOrCreate(['size' => '16oz'], ['price' => 49.00]);
            }
        }
            
        }
    }
}