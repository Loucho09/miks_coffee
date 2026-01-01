<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\SupportTicket;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * PERFORMANCE: Silent Database Monitoring.
         * Prevents console output to ensure zero interference with the PHP process.
         */
        DB::listen(function ($query) {
            if ($query->time > 200) {
                Log::warning("Performance Alert: Slow Query ({$query->time}ms)");
            }
        });

        /**
         * ULTRA-SNAP SINGLETON:
         * We bind the unread count calculation to the view share once.
         * This prevents 'SupportTicket' checks from firing on every sub-component.
         */
        View::composer(['layouts.*', 'dashboard', 'cafe.*', 'public_menu'], function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                
                /**
                 * Uses 'array' driver for request-life persistence.
                 * Database count is cached for 10 minutes to ensure 'Snap' speed.
                 */
                $unreadCount = Cache::remember("support_unread_{$userId}", 600, function () use ($userId) {
                    return SupportTicket::where('user_id', $userId)
                        ->where('status', 'replied')
                        ->count();
                });

                $view->with('unreadSupportCount', $unreadCount);
            } else {
                $view->with('unreadSupportCount', 0);
            }
        });
    }
}