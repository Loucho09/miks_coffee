<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image',
        'is_active',
        'happy_hour_discount',
        'happy_hour_start',
        'happy_hour_end',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Determine if Happy Hour is currently active for this product using Asia/Manila time.
     */
    public function getIsHappyHourActiveAttribute(): bool
    {
        if (!$this->happy_hour_start || !$this->happy_hour_end || !$this->happy_hour_discount) {
            return false;
        }

        $now = Carbon::now('Asia/Manila');
        $start = Carbon::createFromTimeString($this->happy_hour_start, 'Asia/Manila');
        $end = Carbon::createFromTimeString($this->happy_hour_end, 'Asia/Manila');

        if ($end->lessThan($start)) {
            return $now->greaterThanOrEqualTo($start) || $now->lessThanOrEqualTo($end);
        }

        return $now->between($start, $end);
    }

    /**
     * Calculate the live discounted price.
     */
    public function getHappyHourPriceAttribute()
    {
        if ($this->is_happy_hour_active) {
            return (float) $this->price * (1 - ($this->happy_hour_discount / 100));
        }
        return (float) $this->price;
    }

    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function sizes() { return $this->hasMany(ProductSize::class); }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) return 'out_of_stock';
        if ($this->stock_quantity <= 10) return 'low_stock';
        return 'in_stock';
    }
}