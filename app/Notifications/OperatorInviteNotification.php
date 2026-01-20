<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OperatorInviteNotification extends Notification
{
    use Queueable;

    protected string $inviteToken;

    public function __construct(string $inviteToken)
    {
        $this->inviteToken = $inviteToken;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/invite/accept/' . $this->inviteToken);

        return (new MailMessage)
            ->subject(__('invite.subject'))
            ->line(__('invite.line'))
            ->action(__('invite.action'), $url);
    }
}
