<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PointTransaction;

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
        'last_seen_at',
        'referral_code',
        'referred_by',
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
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * 🟢 FEATURE: Automate referral code generation and point distribution.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            // Generate a code if one doesn't exist
            if (empty($user->referral_code)) {
                $user->referral_code = 'MIKS-' . strtoupper(Str::random(6));
            }
            
            // Capture referral from session
            if (session()->has('referrer_code')) {
                $referrer = self::where('referral_code', session('referrer_code'))->first();
                if ($referrer) {
                    $user->referred_by = $referrer->id;
                }
            }
        });

        static::created(function ($user) {
            // Only run if the user was actually referred by someone
            if ($user->referred_by) {
                $referrer = $user->referrer; // This uses the belongsTo relationship
                
                if ($referrer) {
                    // Reward Referrer
                    $referrer->increment('points', 50);
                    
                    // Reward New User
                    $user->increment('points', 50);

                    // Create Logs
                    PointTransaction::create([
                        'user_id' => $referrer->id,
                        'amount' => 50,
                        'description' => "Referral Bonus: {$user->name} joined",
                    ]);

                    PointTransaction::create([
                        'user_id' => $user->id,
                        'amount' => 50,
                        'description' => "Welcome Bonus: Referred by {$referrer->name}",
                    ]);
                }
            }
        });
    }

    /**
     * 🟢 NEW FEATURE: Update Streak Logic
     */
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

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
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