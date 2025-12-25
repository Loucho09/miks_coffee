<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',       // For AdminMiddleware
        'is_admin',       // For IsAdmin Middleware
        'points',
        'role',
        'loyalty_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * 🟢 FIXED: Missing isAdmin() method.
     * Resolves the P1013 "Undefined method" error in Controllers.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || 
               $this->usertype === 'admin' || 
               $this->is_admin === true;
    }

    /**
     * 🟢 NEW FEATURE: Loyalty Tier Accessor.
     * Usage: $user->loyalty_tier
     */
    public function getLoyaltyTierAttribute(): string
    {
        $pts = $this->loyalty_points ?? 0;
        if ($pts >= 500) return 'Gold';
        if ($pts >= 200) return 'Silver';
        return 'Bronze';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getTotalSpentAttribute(): float
    {
        return (float) $this->orders()->sum('total_price');
    }
}