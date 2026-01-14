<?php

namespace App\Jobs;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewsletterIssueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $issueId
    ) {}

    public function handle(): void
    {
        $issue = NewsletterIssue::find($this->issueId);

        if (! $issue || $issue->isSent()) {
            return;
        }

        // snapshot HTML (idempotentny)
        $issue->snapshotHtml();

        // status → sending
        $issue->update([
            'status' => 'sending',
        ]);

        // wysyłka maili
        Subscriber::query()
            ->where('is_active', true)
            ->whereNull('unsubscribed_at')
            ->select('id')
            ->chunkById(500, function ($subs) use ($issue) {
                foreach ($subs as $sub) {
                    SendNewsletterJob::dispatch(
                        $issue,
                        Subscriber::find($sub->id)
                    );
                }
            });

        // finalizacja
        $issue->update([
            'sent_at' => now(),
            'status'  => 'sent',
        ]);
    }
}
