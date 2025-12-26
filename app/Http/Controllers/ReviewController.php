<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review and trigger automated points via Observer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $item = OrderItem::findOrFail($request->order_item_id);

        // 1. Double-check to prevent duplicate points for the same item
        if (Review::where('order_item_id', $item->id)->exists()) {
            return back()->with('error', 'Points already claimed for this brew!');
        }

        // 2. Save the Review
        // NOTE: The ReviewObserver will see this and automatically add +2 PTS to the 'points' column
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $item->product_id,
            'order_item_id' => $item->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted! +2 Points added to your balance.');
    }
}