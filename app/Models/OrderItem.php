<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'size', 
        'customizations', // 🟢 NEW: Stores JSON add-ons
    ];

    /**
     * 🟢 NEW FEATURE: Automatically cast JSON customizations to a PHP array.
     */
    protected $casts = [
        'customizations' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'product_id', 'product_id')
                    ->where(function ($query) {
                        if ($this->order) {
                            $query->where('user_id', $this->order->user_id);
                        } else {
                            /** @var int|null $currentUserId */
                            $currentUserId = Auth::id();
                            $query->where('user_id', $currentUserId);
                        }
                    });
    }

    /**
     * 🟢 UPDATED: Calculate Line Total including customizations.
     */
    public function getLineTotalAttribute(): float
    {
        $unitPrice = (float) $this->price;
        if ($this->customizations) {
            foreach ($this->customizations as $cost) {
                $unitPrice += (float) $cost;
            }
        }
        return (float) ($unitPrice * $this->quantity);
    }
}