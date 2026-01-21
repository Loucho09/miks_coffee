<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Store a newly created review and award loyalty points.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|integer|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // SECURITY FIX: Ensure the item belongs to an order owned by the current user
        $item = OrderItem::where('id', $request->order_item_id)
            ->whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        // 1. Prevent duplicate rewards for the same item
        if (Review::where('order_item_id', $item->id)->exists()) {
            return back()->with('error', 'You have already reviewed this item!');
        }

        DB::beginTransaction();
        try {
            // 2. Save the Review
            Review::create([
                'user_id' => $user->id,
                'product_id' => $item->product_id,
                'order_item_id' => $item->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // 3. Award 2 points to the loyalty_points column
            $user->increment('loyalty_points', 2);

            // 4. Log the transaction in the ledger
            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 2,
                'description' => "Review Reward: " . ($item->product->name ?? 'Brew'),
            ]);

            DB::commit();
            return back()->with('success', 'Review submitted! +2 Points added to your balance.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while saving your review.');
        }
    }
}