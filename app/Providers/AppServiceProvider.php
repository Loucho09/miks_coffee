<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\SupportTicket;
use App\Models\LoginHistory;

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
         * REGISTER VIEW NAMESPACE
         * This fixes the "No hint path defined for [cafe]" error.
         */
        View::addNamespace('cafe', resource_path('views/cafe'));

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
         * Targeted to avoid infinite loops and provide sidebar data.
         * We bind the unread count calculation and login history to the view share once.
         */
        View::composer(['layouts.app', 'dashboard', 'cafe.index', 'public_menu'], function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                
                $unreadCount = Cache::remember("support_unread_{$userId}", 600, function () use ($userId) {
                    return SupportTicket::where('user_id', $userId)
                        ->where('status', 'replied')
                        ->count();
                });

                // Fetch recent login history for the authenticated user
                $loginHistory = LoginHistory::where('user_id', $userId)
                    ->latest('login_at')
                    ->take(5)
                    ->get();

                $view->with([
                    'unreadSupportCount' => $unreadCount,
                    'loginHistory' => $loginHistory
                ]);
            } else {
                $view->with([
                    'unreadSupportCount' => 0,
                    'loginHistory' => collect()
                ]);
            }
        });
    }
}