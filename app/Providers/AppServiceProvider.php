<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword; // <--- Import this
use Illuminate\Notifications\Messages\MailMessage; // <--- Import this

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
        // Keep your existing Gate logic
        Gate::define('isBarista', function ($user) {
            return $user->usertype === 'admin'; // Fixed: Changed 'is_admin' to 'usertype' based on your previous files
        });

        // ☕ CUSTOM EMAIL FOR PASSWORD RESET ☕
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            return (new MailMessage)
                ->subject('☕ Reset Your Miks Coffee Password')
                ->greeting('Hello Coffee Lover!')
                ->line('We received a request to reset your password. No worries, we can help you brew a new one.')
                ->action('Reset Password', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('If you did not request a password reset, you can safely ignore this email. Your account is secure.')
                ->salutation('Cheers, Miks Coffee Team');
        });
    }
}