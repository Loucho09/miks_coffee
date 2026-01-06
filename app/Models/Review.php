<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id', // Required for point tracking logic
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        // 🟢 FIX: Added ::class to resolve the class name correctly
        return $this->belongsTo(Product::class);
    }
    
    public function orderItem()
    {
        // 🟢 FIX: Added ::class to resolve the class name correctly
        return $this->belongsTo(OrderItem::class);
    }
}