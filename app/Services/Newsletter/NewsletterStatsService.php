<?php

namespace App\Services\Newsletter;

use App\Models\NewsletterIssue;
use Illuminate\Support\Facades\Cache;

class NewsletterStatsService
{
    public function getForIssue(NewsletterIssue $issue): array
    {
        $cacheKey = "newsletter:stats:{$issue->id}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            function () use ($issue) {

                $totalOpens = $issue->opens()->count();
                $uniqueOpens = $issue->uniqueOpens();

                $totalClicks = $issue->clicks()->count();
                $uniqueClicks = $issue->uniqueClicks();

                $ctr = $uniqueOpens > 0
                    ? round(($uniqueClicks / $uniqueOpens) * 100, 2)
                    : 0.0;

                return [
                    'opens'         => $totalOpens,
                    'unique_opens'  => $uniqueOpens,
                    'clicks'        => $totalClicks,
                    'unique_clicks' => $uniqueClicks,
                    'ctr'           => $ctr,
                ];
            }
        );
    }
}
