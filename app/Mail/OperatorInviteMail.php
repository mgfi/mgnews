<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class OperatorInviteMail extends Mailable
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('Zaproszenie do panelu')
            ->view('emails.operator-invite');
    }
}
