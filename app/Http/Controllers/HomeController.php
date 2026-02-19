<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topStreaks = User::where('streak_count', '>', 0)
            ->orderBy('streak_count', 'desc')
            ->take(5)
            ->get();

        $featuredProducts = Product::where('is_active', 1)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // 🟢 NEW FEATURE: AI-Powered "Trending Now" (Top 3 in last 24h)
        $trendingProducts = Product::where('is_active', 1)
            ->withCount(['orderItems' => function($query) {
                $query->where('created_at', '>=', now()->subDay());
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('topStreaks', 'featuredProducts', 'trendingProducts'));
    }
}