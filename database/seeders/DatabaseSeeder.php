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
        // 1. Create Users
        User::create([
            'name' => 'Admin User',
            'email' => 'jmloucho09@gmail.com',
            'password' => Hash::make('password'),
            'usertype' => 'admin',
            'points' => 1000,
        ]);

        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'usertype' => 'user',
            'points' => 50,
        ]);

        // 2. Define The Full Menu Data
        $menu = [
            'Rice Meals' => [
                ['name' => 'Chicken Teriyaki', 'price' => 119, 'desc' => 'Savory chicken glazed in teriyaki sauce, served with rice.'],
                ['name' => 'Katsudon', 'price' => 139, 'desc' => 'Breaded pork cutlet and egg simmered in a sweet and savory broth.'],
                ['name' => 'Chicken Pastil', 'price' => 99, 'desc' => 'Shredded chicken topped over steamed rice wrapped in banana leaf.'],
                ['name' => 'Hungarian Sausage', 'price' => 129, 'desc' => 'Juicy Hungarian sausage served with rice and egg.'],
                ['name' => 'Bangus', 'price' => 99, 'desc' => 'Fried milkfish served with rice and egg.'],
                ['name' => 'Grilled Liempo', 'price' => 149, 'desc' => 'Marinated grilled pork belly served with rice.'],
                ['name' => 'Beef Tapa', 'price' => 99, 'desc' => 'Cured beef served with garlic rice and egg.'],
                ['name' => 'Burger Steak', 'price' => 109, 'desc' => 'Beef burger patties with mushroom gravy and rice.'],
                ['name' => 'Curry Katsu', 'price' => 159, 'desc' => 'Breaded cutlet served with savory curry sauce.'],
            ],
            'Best Flavored Chicken' => [
                ['name' => 'Chicken Fingers with Rice', 'price' => 89, 'desc' => 'Crispy chicken fingers served with rice.'],
                ['name' => '2pcs Chicken with Rice', 'price' => 129, 'desc' => 'Two pieces of flavored fried chicken with rice.'],
                ['name' => '6pcs Chicken', 'price' => 269, 'desc' => 'Six pieces of flavored chicken. Flavors: Gravy, Buffalo, Cheesy Bacon, Garlic Parmesan, Salted Egg, Soy Garlic.'],
            ],
            'Frappe' => [
                ['name' => 'Avocado Frappe', 'price' => 99, 'desc' => 'Creamy avocado blended drink.'],
                ['name' => 'Black Forest Frappe', 'price' => 99, 'desc' => 'Chocolate and cherry flavor blend.'],
                ['name' => 'Buko Pandan Frappe', 'price' => 99, 'desc' => 'Classic Filipino coconut and pandan flavor.'],
                ['name' => 'Choco Kisses Frappe', 'price' => 99, 'desc' => 'Rich chocolate blend.'],
                ['name' => 'Choco Mousse Frappe', 'price' => 99, 'desc' => 'Smooth chocolate mousse flavor.'],
                ['name' => 'Cookies and Cream Frappe', 'price' => 99, 'desc' => 'Creamy blend with cookie bits.'],
                ['name' => 'Double Dutch Frappe', 'price' => 99, 'desc' => 'Chocolate and marshmallow blend.'],
                ['name' => 'Leche Flan Frappe', 'price' => 99, 'desc' => 'Caramel custard flavor.'],
                ['name' => 'Mango Frappe', 'price' => 99, 'desc' => 'Sweet mango blend.'],
                ['name' => 'Mocha Frappe', 'price' => 99, 'desc' => 'Coffee and chocolate blend.'],
                ['name' => 'Strawberry Frappe', 'price' => 99, 'desc' => 'Sweet strawberry blend.'],
                ['name' => 'Taro Frappe', 'price' => 99, 'desc' => 'Sweet and nutty taro flavor.'],
                ['name' => 'Ube Frappe', 'price' => 99, 'desc' => 'Purple yam flavor.'],
                ['name' => 'Vanilla Frappe', 'price' => 99, 'desc' => 'Classic creamy vanilla.'],
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
                ['name' => 'Matcha Espresso', 'price' => 99, 'desc' => 'Layered matcha and espresso.'],
                ['name' => 'Spanish Latte', 'price' => 89, 'desc' => 'Espresso with condensed milk.'],
                ['name' => 'Strawberry Espresso', 'price' => 109, 'desc' => 'Espresso with strawberry milk.'],
            ],
            'Non-Coffee Based' => [
                ['name' => 'Biscoff Latte', 'price' => 119, 'desc' => 'Creamy milk with Biscoff flavor.'],
                ['name' => 'Iced Chocolate', 'price' => 79, 'desc' => 'Rich chilled chocolate milk.'],
                ['name' => 'Matcha Latte', 'price' => 89, 'desc' => 'Green tea milk latte.'],
                ['name' => 'Strawberries Latte', 'price' => 99, 'desc' => 'Creamy strawberry milk.'],
            ],
            'Fruit Soda' => [
                ['name' => 'Blueberry Soda', 'price' => 39, 'desc' => 'Refreshing blueberry fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Grapes Soda', 'price' => 39, 'desc' => 'Refreshing grapes fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Green Apple Soda', 'price' => 39, 'desc' => 'Refreshing green apple fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Lemon Soda', 'price' => 39, 'desc' => 'Zesty lemon fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Orange Soda', 'price' => 39, 'desc' => 'Citrus orange fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Strawberry Soda', 'price' => 39, 'desc' => 'Sweet strawberry fruit soda. (12oz: 39, 16oz: 49)'],
                ['name' => 'Watermelon Soda', 'price' => 39, 'desc' => 'Cool watermelon fruit soda. (12oz: 39, 16oz: 49)'],
            ],
            'Waffles' => [
                ['name' => 'Oreo Waffle', 'price' => 89, 'desc' => 'Oreo, crushed oreo, chocolate syrup with whipcream.'],
                ['name' => 'Nutella Waffle', 'price' => 99, 'desc' => 'Nutella and sliced almond.'],
                ['name' => 'Biscoff Waffle', 'price' => 129, 'desc' => 'Biscoff syrup, crushed biscoff, biscoff biscuit and whipcream.'],
                ['name' => 'Smores Waffle', 'price' => 79, 'desc' => 'Marshmallow and chocolate syrup.'],
                ['name' => 'Banana Nutella Waffle', 'price' => 119, 'desc' => 'Nutella and banana.'],
                ['name' => 'Matcha Waffle', 'price' => 89, 'desc' => 'Matcha syrup and whipcream.'],
                ['name' => 'Strawberry Waffle', 'price' => 139, 'desc' => 'Whipcream, syrup and strawberry.'],
                ['name' => 'Ham and Cheese Waffle', 'price' => 109, 'desc' => 'Ham, cheese and mayo sauce.'],
            ],
            'Pasta & Sandwiches' => [
                ['name' => 'Ham and Cheese Sandwich', 'price' => 125, 'desc' => 'Ham, cheese spread, cheese slice, cucumber, tomato and lettuce.'],
                ['name' => 'Overload Fries', 'price' => 79, 'desc' => 'Fries, cheese, cheese sauce and beef.'],
                ['name' => 'Penne Carbonara', 'price' => 149, 'desc' => 'Pasta, garlic bread, sauce, cheese and bacon.'],
                ['name' => 'Tuna Sandwich', 'price' => 99, 'desc' => 'Tuna, spread, tomato, cucumber and lettuce.'],
            ],
            'Desserts' => [
                ['name' => 'Mini Donuts (6pcs)', 'price' => 60, 'desc' => 'Overload Mini Donuts. Choose your toppings and flavor.'],
                ['name' => 'Mini Donuts (12pcs)', 'price' => 105, 'desc' => 'Overload Mini Donuts. Choose your toppings and flavor.'],
            ]
        ];

        // 3. Loop through Categories and Insert Products
        foreach ($menu as $categoryName => $products) {
            // Create the Category
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            // Create each Product in that Category
            foreach ($products as $item) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']), // Generates URL-friendly slug
                    'price' => $item['price'],
                    'description' => $item['desc'],
                    'stock_quantity' => 50, // Default stock
                    'is_active' => true,
                ]);
            }
        }
    }
}