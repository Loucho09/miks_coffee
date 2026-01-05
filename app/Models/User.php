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
use App\Models\LoginHistory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
        'points',
        'role',
        'loyalty_points',
        'streak_count',
        'last_visit_at', 
        'last_seen_at',
        'referral_code',
        'referred_by',
        'last_session_id',
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
            'last_visit_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = 'MIKS-' . strtoupper(Str::random(6));
            }
            if (session()->has('referrer_code')) {
                $referrer = self::where('referral_code', session('referrer_code'))->first();
                if ($referrer) {
                    $user->referred_by = $referrer->id;
                }
            }
        });

        static::created(function ($user) {
            if ($user->referred_by) {
                $referrer = $user->referrer;
                if ($referrer) {
                    // 🟢 Standardized to loyalty_points for bonuses (Ensures 68 PTS sync)
                    $referrer->increment('loyalty_points', 50);
                    $user->increment('loyalty_points', 50);
                    
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

    public function updateStreak(): bool
    {
        $today = Carbon::today();
        $lastVisit = $this->last_visit_at ? Carbon::parse($this->last_visit_at)->startOfDay() : null;

        if (!$lastVisit) {
            $this->streak_count = 1;
        } elseif ($lastVisit->isToday()) {
            return false;
        } elseif ($lastVisit->isYesterday()) {
            $this->streak_count++;
        } else {
            $this->streak_count = 1;
        }

        $this->last_visit_at = now();
        $this->save();

        if ($this->streak_count >= 3 && $this->streak_count % 3 === 0) {
            // 🟢 Standardized to loyalty_points for streak rewards
            $this->increment('loyalty_points', 20);
            PointTransaction::create([
                'user_id' => $this->id,
                'amount' => 20,
                'description' => "Streak Milestone: {$this->streak_count} Day Order Streak reached",
            ]);
        }

        return true;
    }

    /**
     * 🟢 Standardized Admin Check
     * Validates admin access against DB records and the Master Bypass email.
     */
    public function isAdmin(): bool
    {
        return strtolower($this->usertype ?? '') === 'admin' || 
               strtolower($this->role ?? '') === 'admin' || 
               $this->email === 'jmloucho09@gmail.com';
    }

    /**
     * 🟢 Standardized Tier Logic
     * Dynamically calculates status based on the 68 PTS balance.
     */
    public function getLoyaltyTierAttribute(): string
    {
        $pts = $this->loyalty_points ?? 0;
        if ($pts >= 500) return 'Gold';
        if ($pts >= 200) return 'Silver';
        return 'Bronze';
    }

    public function referrer(): BelongsTo { return $this->belongsTo(User::class, 'referred_by'); }
    public function referrals(): HasMany { return $this->hasMany(User::class, 'referred_by'); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function pointTransactions(): HasMany { return $this->hasMany(PointTransaction::class); }
    public function loginHistory(): HasMany { return $this->hasMany(LoginHistory::class); }
    public function getTotalSpentAttribute(): float { return (float) $this->orders()->sum('total_price'); }
}