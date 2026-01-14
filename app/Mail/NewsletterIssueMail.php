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

        /**
         * ✅ używamy wbudowanego Mailable::$locale
         * (public ?string $locale)
         */
        $this->locale = $subscriber->locale
            ?? config('app.locale', 'pl');
    }

    public function build(): self
    {
        return $this
            ->subject(
                $this->issue->subject($this->locale)
            )
            ->view('emails.newsletter_issue')
            ->text('emails.newsletter_issue_text')
            ->with([
                'html' => $this->issue->content_html,
                'subscriber' => $this->subscriber,
                'previewText' => $this->issue->previewText($this->locale),
                'issue' => $this->issue,
                'locale' => $this->locale,
            ]);
    }
}
