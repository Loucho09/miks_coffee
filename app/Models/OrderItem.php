<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth; // 🟢 Alternative: Use the Auth facade

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'size', 
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 🟢 ALTERNATIVE FIX: Link item to its review.
     * We use a type-hinted variable to satisfy the Intelephense extension
     * and prevent the "Undefined method id" error.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'product_id', 'product_id')
                    ->where(function ($query) {
                        if ($this->order) {
                            $query->where('user_id', $this->order->user_id);
                        } else {
                            /** @var int|null $currentUserId */
                            $currentUserId = Auth::id(); // Use Facade for better IDE support
                            $query->where('user_id', $currentUserId);
                        }
                    });
    }

    /**
     * 🟢 NEW FEATURE: Line Total Calculation
     */
    public function getLineTotalAttribute(): float
    {
        return (float) ($this->price * $this->quantity);
    }
}