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
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'last_session_id',
        'is_online',
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
            'is_online' => 'boolean',
            'last_visit_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Ensure is_online is always returned as a strict boolean.
     * This fixes the "empty" display issues in Tinker/SQLite.
     */
    protected function isOnline(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (bool) $value,
            set: fn ($value) => (bool) $value,
        );
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
                    $referrer->increment('points', 50);
                    $user->increment('points', 50);
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
            $this->increment('points', 20);
            PointTransaction::create([
                'user_id' => $this->id,
                'amount' => 20,
                'description' => "Streak Milestone: {$this->streak_count} Day Order Streak reached",
            ]);
        }

        return true;
    }

    /**
     * Optimized Admin Check for speed.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || 
               $this->usertype === 'admin' || 
               $this->is_admin === true ||
               $this->email === 'jmloucho09@gmail.com';
    }

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