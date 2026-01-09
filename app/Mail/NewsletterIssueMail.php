<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterIssueMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public NewsletterIssue $issue;
    public Subscriber $subscriber;

    public function __construct(
        NewsletterIssue $issue,
        Subscriber $subscriber
    ) {
        $this->issue = $issue;
        $this->subscriber = $subscriber;
    }

    public function build(): self
    {
        return $this
            ->subject($this->issue->subject)
            ->view('emails.newsletter_issue')
            ->with([
                'html' => $this->issue->content_html,
                'subscriber' => $this->subscriber,
            ]);
    }
}
