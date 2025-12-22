<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\PointTransaction; 
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        // 1. Save the Review
        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 2. Increment the loyalty points column in users table
        $user->increment('loyalty_points', 2);

        // 3. 🟢 NEW FEATURE: Create the Point Transaction log
        PointTransaction::create([
            'user_id' => $user->id,
            'amount' => 2,
            'description' => 'Review Reward: ' . ($review->product->name ?? 'Product'),
            'reference_type' => 'review',
            'reference_id' => $review->id,
        ]);

        return back()->with('success', 'Review submitted successfully! You earned 2 loyalty points. ');
    }
}