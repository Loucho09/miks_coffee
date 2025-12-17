<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price', // This becomes the "Base Price" or 12oz price default
        'stock_quantity',
        'image',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🟢 ADD THIS RELATIONSHIP
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}