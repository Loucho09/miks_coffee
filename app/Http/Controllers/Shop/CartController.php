<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('cafe.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        // 🟢 FIXED: Handle defaults if size/price aren't provided by the modal logic
        $size = $request->size ?: 'Regular';
        $price = $request->price ?: $product->price;

        // Create a unique key based on Product ID and Size 
        $cartKey = $product->id . '_' . $size;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => (int) $product->id, // 🟢 Force integer ID storage
                "name" => $product->name,
                "quantity" => 1,
                "price" => $price, 
                "size" => $size,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Brew added to cart!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed.');
        }
    }
}