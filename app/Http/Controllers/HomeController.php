<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page with the Public Streak Leaderboard and Featured Products.
     */
    public function index()
    {
        // Fetch top 5 users with active streaks for the leaderboard
        $topStreaks = User::where('streak_count', '>', 0)
            ->orderBy('streak_count', 'desc')
            ->take(5)
            ->get();

        // Fetch 3 random active products for the "Taste the Magic" section
        $featuredProducts = Product::where('is_active', 1)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('welcome', compact('topStreaks', 'featuredProducts'));
    }
}