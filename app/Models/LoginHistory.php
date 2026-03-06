<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'login_at'
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    /**
     * Accessor to ensure every timestamp is converted to Manila time for the UI.
     */
    public function getLoginAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Manila');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}