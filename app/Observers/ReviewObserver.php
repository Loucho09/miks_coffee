<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\PointTransaction;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        /** @var \App\Models\User $user */
        $user = $review->user;

        if ($user) {
            // 1. Increment standardized 'points' column
            $user->increment('points', 2);

            // 2. Log transaction for the Rewards History page
            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 2,
                'description' => 'Review Reward: ' . ($review->product->name ?? 'Brew'),
                'reference_type' => 'review',
                'reference_id' => $review->id,
            ]);
        }
    }
}