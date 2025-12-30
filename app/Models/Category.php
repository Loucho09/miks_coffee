<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // 🟢 Required for slugging

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image', // 🟢 Added for Category Banners
        'is_active',
    ];

    /**
     * 🟢 NEW FEATURE: Automate slug generation on save
     * This ensures the slug is generated from the name before saving to the database.
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}