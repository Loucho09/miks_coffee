<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
                
class HomeController extends Controller
{
    public function index()           
    {
        /** 
         * UPDATED: Public Standings query to exclude Admin accounts.
         * This ensures only regular customers appear on the landing page leaderboard.
         */
        $topStreaks = User::where('streak_count', '>', 0)
            ->where('usertype', '!=', 'admin')
            ->where('role', '!=', 'admin')
            ->where('email', '!=', 'jmloucho09@gmail.com')
            ->orderBy('streak_count', 'desc')
            ->take(5)
            ->get();

        $featuredProducts = Product::where('is_active', 1)
            ->inRandomOrder()
            ->take(3)
            ->get();
            
        $trendingProducts = Product::where('is_active', 1)
            ->withCount(['orderItems' => function($query) {
                $query->where('created_at', '>=', now()->subDay());
            }])
            ->orderBy('order_items_count', 'desc')
                 ->take(3)
                   ->get();

         // 🟢 FEATURE: Check if any active Happy Hour exists
        $isHappyHour = Product::where('is_active', 1)
            ->get()
              ->some(fn($p) => $p->is_happy_hour_active);
  
        return view('welcome', compact('topStreaks', 'featuredProducts', 'trendingProducts', 'isHappyHour'));
    }
}  