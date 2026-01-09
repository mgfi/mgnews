<?php

namespace App\Jobs;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use App\Mail\NewsletterIssueMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Mail\Mailable;

class SendNewsletterIssueEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $newsletterIssueId,
        public int $subscriberId
    ) {}

    public function handle(): void
    {
        $issue = NewsletterIssue::find($this->newsletterIssueId);
        $subscriber = Subscriber::find($this->subscriberId);

        // fail-safe
        if (! $issue || ! $subscriber || $subscriber->status !== 'active') {
            return;
        }

        Mail::to($subscriber->email)
            ->send(new NewsletterIssueMail($issue, $subscriber));
    }

    public function failed(Throwable $exception): void
    {
        // Na razie NIC – logi są w F3.3
    }
}
