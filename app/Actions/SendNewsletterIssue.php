<?php

namespace App\Actions;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use App\Jobs\SendNewsletterIssueEmailJob;
use App\Services\Newsletter\NewsletterSendValidator;

class SendNewsletterIssue
{
    public static function run(int $newsletterId): void
    {
        $newsletter = NewsletterIssue::findOrFail($newsletterId);

        app(NewsletterSendValidator::class)->validate($newsletter);

        $newsletter->update([
            'status' => 'sending',
        ]);

        Subscriber::where('is_active', 1)
            ->whereNull('deleted_at')
            ->select('id')
            ->chunk(100, function ($subscribers) use ($newsletter) {
                foreach ($subscribers as $subscriber) {
                    SendNewsletterIssueEmailJob::dispatch(
                        $newsletter->id,
                        $subscriber->id
                    );
                }
            });
    }
}
