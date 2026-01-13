<?php

namespace App\Services;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use App\Jobs\SendNewsletterJob;

class NewsletterSender
{
    public function send(NewsletterIssue $issue): void
    {
        // zabezpieczenie
        if (! $issue) {
            return;
        }

        // oznacz newsletter jako wysyłany
        $issue->update([
            'status' => 'sending',
        ]);

        // pobierz tylko aktywnych subskrybentów
        Subscriber::where('is_active', true)
            ->chunk(100, function ($subscribers) use ($issue) {
                foreach ($subscribers as $subscriber) {
                    SendNewsletterJob::dispatch($issue, $subscriber);
                }
            });

        // oznacz newsletter jako wysłany
        $issue->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }
}
