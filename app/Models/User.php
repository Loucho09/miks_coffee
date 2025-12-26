<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
        'is_admin',
        'points',
        'role',
        'loyalty_points',
        'streak_count',
        'last_visit_at', 
        'last_seen_at', // 🟢 REQUIRED: Allows the status to be saved
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
            'last_visit_at' => 'datetime',
            'last_seen_at' => 'datetime', // 🟢 REQUIRED: Ensures time is handled correctly
        ];
    }

    public function updateStreak(): void
    {
        $today = Carbon::today();
        $lastVisit = $this->last_visit_at ? Carbon::parse($this->last_visit_at)->startOfDay() : null;

        if (!$lastVisit) {
            $this->streak_count = 1;
        } elseif ($lastVisit->isYesterday()) {
            $this->streak_count++;
        } elseif ($lastVisit->isBefore($today->subDay())) {
            $this->streak_count = 1;
        }

        $this->last_visit_at = now();
        $this->save();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || 
               $this->usertype === 'admin' || 
               $this->is_admin === true;
    }

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