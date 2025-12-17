<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cafe.cart'); 
    }

    // 🟢 UPDATED ADD FUNCTION TO HANDLE SIZES
    public function add(Request $request)
    {
        $productId = $request->product_id;
        $sizeId = $request->size_id; // This might be null for food items

        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        // Determine Price and Unique Cart ID
        $price = $product->price;
        $sizeName = 'Regular';
        $cartKey = $productId; // Default key for items without sizes

        // If a size was selected
        if ($sizeId) {
            $sizeOption = ProductSize::findOrFail($sizeId);
            $price = $sizeOption->price;
            $sizeName = $sizeOption->size;
            // Create a unique key like "1_12oz" so we can have both sizes in cart
            $cartKey = $productId . '_' . $sizeName; 
        }

        // Logic to add or increment
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $price,
                "size" => $sizeName, // Save the size name
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Removed successfully!');
        }
    }
}