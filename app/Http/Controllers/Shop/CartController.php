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
            $itemPrice = (float) $details['price'];
            if (isset($details['customizations']) && is_array($details['customizations'])) {
                foreach ($details['customizations'] as $addonPrice) {
                    $itemPrice += (float) $addonPrice;
                }
            }
            $total += $itemPrice * $details['quantity'];
        }

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
        
        $isHH = $product->is_happy_hour_active;
        $basePrice = $isHH ? $product->happy_hour_price : ($request->price ?: $product->price);

        $customizations = [];
        if ($request->filled('customizations')) {
            $customizations = json_decode($request->customizations, true) ?: [];
        }

        $customizationHash = md5(json_encode($customizations));
        $cartKey = $product->id . '_' . $size . '_' . $customizationHash;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => (int) $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => (float) $basePrice, 
                "size" => $size,
                "image" => $product->image,
                "is_happy_hour" => $isHH,
                "customizations" => $customizations 
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