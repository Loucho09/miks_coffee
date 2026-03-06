<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'total_price',
        'status',
        'payment_method',
        'points_earned',
        'points_redeemed',
        'reward_type',
        'order_number',
        'notes',
        'qr_claim_token', // SECURE: Added for single-use QR logic
        'points_awarded',  // SECURE: Added to prevent multiple point claims
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}