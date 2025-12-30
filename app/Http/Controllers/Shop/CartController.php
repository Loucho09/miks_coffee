<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
            $totalItems += $details['quantity'];
        }

        // Calculate Bulk Savings for the UI
        $bulkSavings = ($totalItems >= 6) ? ($subtotal * 0.10) : 0;

        return view('cafe.cart', compact('cart', 'subtotal', 'totalItems', 'bulkSavings'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if(!$product) {
            return redirect()->back()->with('error', 'Product not found!');
        }

        $sizeName = null;
        $price = $product->price;
        $cartKey = $product->id; 

        if ($request->has('size_id') && $request->size_id) {
            $size = ProductSize::find($request->size_id);
            if ($size) {
                $sizeName = $size->size;
                $price = $size->price;
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
                "size" => $sizeName
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