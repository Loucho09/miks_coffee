<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {
        //
    }

    public function boot(): void
    {
        // 🟢 NEW FEATURE: Share unread support reply count globally
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadSupportCount = SupportTicket::where('user_id', Auth::id())
                    ->where('status', 'replied')
                    ->count();
                $view->with('unreadSupportCount', $unreadSupportCount);
            }
        });
    }
}