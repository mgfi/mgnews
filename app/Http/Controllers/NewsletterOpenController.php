<?php

namespace App\Http\Controllers;

use App\Models\NewsletterIssue;
use App\Models\NewsletterOpen;
use App\Models\Subscriber;
use Illuminate\Http\Response;

class NewsletterOpenController extends Controller
{
    public function open(
        NewsletterIssue $issue,
        ?Subscriber $subscriber = null
    ): Response {
        // Guard: tylko wysłane newslettery
        if (! $issue->isSent()) {
            return $this->pixel();
        }

        // Guard: jeśli subscriber istnieje, ale jest wypisany
        if ($subscriber && ! $subscriber->is_active) {
            return $this->pixel();
        }

        $alreadyOpened = NewsletterOpen::where('newsletter_issue_id', $issue->id)
            ->where('subscriber_id', $subscriber?->id)
            ->exists();

        if (! $alreadyOpened) {
            NewsletterOpen::create([
                'newsletter_issue_id' => $issue->id,
                'subscriber_id'       => $subscriber?->id,
                'opened_at'           => now(),
                'user_agent'          => request()->userAgent(),
            ]);
        }

        return $this->pixel();
    }

    /**
     * Zwraca prawdziwy 1×1 transparent pixel
     */
    protected function pixel(): Response
    {
        // base64 PNG 1x1 transparent
        $pixel = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIW2NkYGD4DwABBAEAHcKk5QAAAABJRU5ErkJggg=='
        );

        return response($pixel, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}
