<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

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
        ResetPassword::toMailUsing(function ($notifiable, string $token) {

            $url = url(route(
                'password.reset',
                ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()],
                false
            ));

            return (new MailMessage)
                ->subject(__('passwords.reset_subject'))
                ->markdown('emails.password-reset', [
                    'url' => $url,
                ]);
        });
    }
}
