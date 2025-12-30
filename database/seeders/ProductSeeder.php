<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow clearing the tables safely
        DB::statement('PRAGMA foreign_keys = OFF;');
        Product::truncate();
        Category::truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        // Data from your Menu Images
        $menu = [
            'Rice Meals' => [
                ['name' => 'Chicken Teriyaki', 'price' => 119, 'desc' => 'Savory chicken glazed in teriyaki sauce with rice.'],
                ['name' => 'Katsudon', 'price' => 139, 'desc' => 'Breaded pork cutlet and egg simmered in savory broth.'],
                ['name' => 'Chicken Pastil', 'price' => 99, 'desc' => 'Shredded chicken topped over steamed rice.'],
                ['name' => 'Hungarian Sausage', 'price' => 129, 'desc' => 'Juicy Hungarian sausage served with rice and egg.'],
                ['name' => 'Bangus', 'price' => 99, 'desc' => 'Fried milkfish served with rice and egg.'],
                ['name' => 'Grilled Liempo', 'price' => 149, 'desc' => 'Marinated grilled pork belly served with rice.'],
                ['name' => 'Beef Tapa', 'price' => 99, 'desc' => 'Cured beef served with garlic rice and egg.'],
                ['name' => 'Burger Steak', 'price' => 109, 'desc' => 'Beef burger patties with mushroom gravy and rice.'],
                ['name' => 'Curry Katsu', 'price' => 159, 'desc' => 'Breaded cutlet served with savory curry sauce.'],
            ],
            'Flavored Chicken' => [
                ['name' => 'Chicken Fingers w/ Rice', 'price' => 89, 'desc' => 'Crispy chicken fingers served with rice.'],
                ['name' => '2pcs Chicken w/ Rice', 'price' => 129, 'desc' => 'Two pieces of flavored fried chicken with rice.'],
                ['name' => '6pcs Chicken', 'price' => 269, 'desc' => 'Flavor Explosion! Gravy, Buffalo, Cheesy Bacon, Garlic Parmesan, or Salted Egg.'],
            ],
            'Frappe' => [
                ['name' => 'Avocado Frappe', 'price' => 99, 'desc' => 'Creamy avocado blended drink.'],
                ['name' => 'Black Forest Frappe', 'price' => 99, 'desc' => 'Chocolate and cherry flavor blend.'],
                ['name' => 'Buko Pandan Frappe', 'price' => 99, 'desc' => 'Classic Filipino coconut and pandan flavor.'],
                ['name' => 'Choco Kisses', 'price' => 99, 'desc' => 'Rich chocolate blend with kisses.'],
                ['name' => 'Cookies and Cream', 'price' => 99, 'desc' => 'Creamy blend with cookie bits.'],
                ['name' => 'Mango Frappe', 'price' => 99, 'desc' => 'Sweet mango blend.'],
                ['name' => 'Taro Frappe', 'price' => 99, 'desc' => 'Sweet and nutty taro flavor.'],
                ['name' => 'Ube Frappe', 'price' => 99, 'desc' => 'Purple yam flavor.'],
            ],
            'Coffee Frappe' => [
                ['name' => 'Cappucino Frappe', 'price' => 119, 'desc' => 'Iced blended cappuccino.'],
                ['name' => 'Coffee Crumble', 'price' => 119, 'desc' => 'Coffee blend with cookie crumbles.'],
                ['name' => 'Caramel Macchiato Frappe', 'price' => 119, 'desc' => 'Iced blended caramel and coffee.'],
            ],
            'Signature Drinks' => [
                ['name' => 'Biscoff Dream Frappe', 'price' => 159, 'desc' => 'Signature blend with Biscoff spread and cookies.'],
                ['name' => 'Matcha Heaven Frappe', 'price' => 139, 'desc' => 'Premium matcha green tea blend.'],
                ['name' => 'Mocha Cafe', 'price' => 119, 'desc' => 'Our signature mocha blend.'],
            ],
            'Coffee Based' => [
                ['name' => 'Americano', 'price' => 79, 'desc' => 'Espresso with hot water.'],
                ['name' => 'Biscoffee', 'price' => 129, 'desc' => 'Coffee infused with Biscoff flavor.'],
                ['name' => 'Cappuccino', 'price' => 89, 'desc' => 'Espresso with steamed milk foam.'],
                ['name' => 'Caramel Macchiato', 'price' => 99, 'desc' => 'Espresso with steamed milk and caramel drizzle.'],
                ['name' => 'Spanish Latte', 'price' => 89, 'desc' => 'Espresso with condensed milk.'],
                ['name' => 'Strawberry Espresso', 'price' => 109, 'desc' => 'Espresso with strawberry milk.'],
            ],
            'Fruit Soda' => [
                ['name' => 'Blueberry Soda', 'price' => 39, 'desc' => 'Refreshing blueberry fruit soda (12oz).'],
                ['name' => 'Green Apple Soda', 'price' => 39, 'desc' => 'Refreshing green apple fruit soda (12oz).'],
                ['name' => 'Strawberry Soda', 'price' => 39, 'desc' => 'Sweet strawberry fruit soda (12oz).'],
            ],
            'Waffles' => [
                ['name' => 'Oreo Waffle', 'price' => 89, 'desc' => 'Oreo, crushed oreo, chocolate syrup with whipcream.'],
                ['name' => 'Nutella Waffle', 'price' => 99, 'desc' => 'Nutella and sliced almond.'],
                ['name' => 'Biscoff Waffle', 'price' => 129, 'desc' => 'Biscoff syrup, crushed biscoff, and whipcream.'],
                ['name' => 'Ham and Cheese Waffle', 'price' => 109, 'desc' => 'Ham, cheese and mayo sauce.'],
            ],
            'Pasta & Sandwiches' => [
                ['name' => 'Ham & Cheese Sandwich', 'price' => 125, 'desc' => 'Ham, cheese spread, cucumber, tomato and lettuce.'],
                ['name' => 'Overload Fries', 'price' => 79, 'desc' => 'Fries, cheese, cheese sauce and beef.'],
                ['name' => 'Penne Carbonara', 'price' => 149, 'desc' => 'Pasta, garlic bread, sauce, cheese and bacon.'],
                ['name' => 'Tuna Sandwich', 'price' => 99, 'desc' => 'Tuna, spread, tomato, cucumber and lettuce.'],
            ],
            'Desserts' => [
                ['name' => 'Mini Donuts (6pcs)', 'price' => 60, 'desc' => 'Overload Mini Donuts. Assorted flavors.'],
                ['name' => 'Mini Donuts (12pcs)', 'price' => 105, 'desc' => 'Overload Mini Donuts. Assorted flavors.'],
            ]
        ];

        foreach ($menu as $categoryName => $items) {
            // 🟢 NEW FEATURE: The seeder now assigns a header image to each category
            $category = Category::create([
                'name' => $categoryName,
                'image' => 'banners/' . Str::slug($categoryName) . '.jpg',
                'is_active' => true
            ]);

            foreach ($items as $item) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'price' => $item['price'],
                    'image' => 'products/' . Str::slug($item['name']) . '.jpg',
                    'stock_quantity' => 50,
                    'is_active' => true
                ]);
            }
        }
    }
}