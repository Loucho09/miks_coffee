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

        // Get Size Logic
        $sizeName = null;
        $price = $product->price;
        $cartKey = $product->id; // Default key

        // If a size was selected (e.g., from the modal)
        if ($request->has('size_id') && $request->size_id) {
            $size = ProductSize::find($request->size_id);
            if ($size) {
                $sizeName = $size->size;   // "16oz"
                $price = $size->price;     // 49.00
                $cartKey = $product->id . '_' . $size->size; // Unique Key: "46_16oz"
            }
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id, // Store the real ID separate from the key
                "name" => $product->name,
                "quantity" => 1,
                "price" => $price,
                "image" => $product->image,
                "size" => $sizeName // 🟢 SAVING SIZE TO SESSION
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed successfully');
        }
    }
}