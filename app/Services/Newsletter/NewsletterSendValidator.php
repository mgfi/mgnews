<?php

namespace App\Services\Newsletter;

use App\Models\NewsletterIssue;
use App\Models\Subscriber;
use DomainException;

class NewsletterSendValidator
{
    public function validate(NewsletterIssue $newsletter): void
    {
        // Status
        if ($newsletter->status !== 'draft') {
            throw new DomainException('Newsletter nie jest w statusie draft.');
        }

        // Bloki / treść
        if (
            empty($newsletter->content_json) ||
            !is_array($newsletter->content_json) ||
            count($newsletter->content_json) === 0
        ) {
            throw new DomainException('Newsletter nie posiada żadnych bloków.');
        }

        // Subskrybenci
        if (
            Subscriber::where('is_active', 1)
            ->whereNull('deleted_at')
            ->count() === 0
        ) {
            throw new DomainException('Brak aktywnych subskrybentów.');
        }
    }
}
