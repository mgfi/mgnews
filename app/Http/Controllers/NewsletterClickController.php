<?php

namespace App\Http\Controllers;

use App\Models\NewsletterClick;
use App\Models\NewsletterIssue;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class NewsletterClickController extends Controller
{
    public function click(string $hash): Response|RedirectResponse
    {
        $click = NewsletterClick::where('hash', $hash)->first();

        if (! $click) {
            return response()->noContent();
        }

        $redirectUrl = $click->target_url;

        $issue = NewsletterIssue::find($click->newsletter_issue_id);
        if (! $issue || ! $issue->isSent()) {
            return redirect()->away($redirectUrl);
        }

        if ($click->subscriber && ! $click->subscriber->is_active) {
            return redirect()->away($redirectUrl);
        }

        // deduplikacja: zapis tylko pierwszego kliknięcia per (issue + subscriber + target)
        if (is_null($click->clicked_at)) {
            $click->update([
                'clicked_at' => now(),
                'user_agent' => request()->userAgent(),
            ]);

            Cache::forget("newsletter:stats:{$click->newsletter_issue_id}");
        }
        // else → klik już był, tylko redirect

        return redirect()->away($redirectUrl);
    }
}
