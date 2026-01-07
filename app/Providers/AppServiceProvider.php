<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
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
         * SECURITY: Automatic Login Tracking
         * Captures IP and Browser data upon successful login.
         */
        Event::listen(Login::class, function ($event) {
            LoginHistory::create([
                'user_id' => $event->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'login_at' => now(),
            ]);
        });

        /**
         * ULTRA-SNAP SINGLETON:
         * We bind the unread count calculation to the view share once.
         */
        View::composer(['layouts.*', 'dashboard', 'cafe.*', 'public_menu'], function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                
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