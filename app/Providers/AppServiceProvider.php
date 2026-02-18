<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        ResetPassword::toMailUsing(function ($notifiable, string $token) {

            $url = url(route(
                'password.reset',
                [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset()
                ],
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
