<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase; // Resets database for every test (clean slate)

    public function test_customer_can_view_menu_add_to_cart_and_checkout()
    {
        // 1. ARRANGEMENT
        $user = User::factory()->create([
            'name' => 'Coffee Lover',
            'email' => 'customer@test.com',
        ]);

        $category = Category::create(['name' => 'Rice Meals', 'slug' => 'rice-meals', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Chicken Teriyaki',
            'slug' => 'chicken-teriyaki',
            'category_id' => $category->id,
            'description' => 'Yummy chicken',
            'price' => 150.00,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        // 2. ACTION
        $this->get(route('menu.index'));
        $this->get(route('cart.add', $product->id));
        $this->actingAs($user);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Coffee Lover',
            'customer_email' => 'customer@test.com',
            'payment_method' => 'cash',
        ]);

        // 3. ASSERTION
        // FIXED: Expect redirect to 'orders.index' instead of 'home'
        $response->assertRedirect(route('orders.index')); 
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'customer@test.com',
            'total_amount' => 150.00,
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 9
        ]);
    }

    public function test_customer_cannot_order_out_of_stock_item()
    {
        // Create an Out of Stock Product
        $category = Category::create(['name' => 'Frappe', 'slug' => 'frappe', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Sold Out Coffee',
            'slug' => 'sold-out',
            'category_id' => $category->id,
            'price' => 100,
            'stock_quantity' => 0, // <--- ZERO STOCK
            'is_active' => true
        ]);

        // Try to Add to Cart
        $response = $this->get(route('cart.add', $product->id));

        // Should NOT succeed
        $response->assertSessionHas('error'); 
        
        // Cart should be empty in session
        $this->assertEmpty(session('cart'));
    }
}