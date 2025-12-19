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
        $cart = session()->get('cart', []);
        return view('shop.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if(!$product) {
            return redirect()->back()->with('error', 'Product not found!');
        }

        // Default values
        $sizeName = null;
        $price = $product->price;
        $cartKey = $product->id; 

        // 🟢 Size Selection Logic
        if ($request->has('size_id') && $request->size_id) {
            $size = ProductSize::find($request->size_id);
            if ($size) {
                $sizeName = $size->size;   // e.g., "16oz"
                $price = $size->price;     // e.g., 140.00
                // Unique Key ensures different sizes stay separate in the cart
                $cartKey = $product->id . '_' . $size->size; 
            }
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $price,
                "image" => $product->image,
                "size" => $sizeName // 🟢 Size stored in session
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart successfully!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed');
        }
    }
}