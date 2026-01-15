<?php

namespace App\Services\Newsletter;

use App\Models\NewsletterIssue;
use Illuminate\Support\Facades\Cache;

class NewsletterStatsService
{
    public const CACHE_TTL_MINUTES = 10;

    /**
     * Publiczny punkt wejścia – pobranie statystyk dla issue
     */
    public function getForIssue(NewsletterIssue $issue): array
    {
        return Cache::remember(
            self::cacheKey($issue->id),
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn() => $this->calculate($issue)
        );
    }

    /**
     * Klucz cache dla konkretnego newslettera
     */
    public static function cacheKey(int $issueId): string
    {
        return "newsletter:stats:{$issueId}";
    }

    /**
     * Faktyczne liczenie statystyk (bez cache)
     */
    protected function calculate(NewsletterIssue $issue): array
    {
        $totalOpens   = $issue->opens()->count();
        $uniqueOpens  = $issue->uniqueOpens();

        $totalClicks  = $issue->clicks()->count();
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
}
