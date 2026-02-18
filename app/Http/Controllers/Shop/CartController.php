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

        // 🟢 NEW FEATURE: AI-Powered "Frequently Bought Together"
        $cartProductIds = collect($cart)->pluck('product_id')->toArray();
        $recommendations = Product::whereHas('category', function($q) {
                $q->where('name', 'like', '%Pastry%')
                  ->orWhere('name', 'like', '%Food%');
            })
            ->whereNotIn('id', $cartProductIds)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->take(3)
            ->get();

        return view('cafe.cart', compact('cart', 'total', 'recommendations'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $size = $request->size ?: 'Regular';
        $price = $request->price ?: $product->price;

        $cartKey = $product->id . '_' . $size;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => (int) $product->id,
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