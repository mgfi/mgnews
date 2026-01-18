<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Subscriber;
use App\Models\NewsletterIssue;
use App\Models\NewsletterOpen;
use App\Models\NewsletterClick;

class DemoEngagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pobierz wysłany newsletter DEMO
        $issue = NewsletterIssue::where('is_demo', true)
            ->where('status', 'sent')
            ->first();

        if (!$issue) {
            return;
        }

        // Pobierz aktywnych subskrybentów DEMO
        $subscribers = Subscriber::where('is_demo', true)
            ->where('is_active', true)
            ->get();

        if ($subscribers->isEmpty()) {
            return;
        }

        foreach ($subscribers as $subscriber) {

            // =========================
            // OPENS (~55%)
            // =========================
            if (rand(1, 100) <= 55) {
                NewsletterOpen::create([
                    'newsletter_issue_id' => $issue->id,
                    'subscriber_id' => $subscriber->id,
                    'opened_at' => now()->subDays(rand(1, 10)),
                    'user_agent' => 'Mozilla/5.0 (Demo)',
                ]);
            }

            // =========================
            // CLICKS (~18%)
            // =========================
            if (rand(1, 100) <= 18) {
                NewsletterClick::create([
                    'newsletter_issue_id' => $issue->id,
                    'subscriber_id' => $subscriber->id,

                    'target_type' => 'url',
                    'target_url' => 'https://example.com/demo-product',

                    'hash' => Str::random(64),
                    'clicked_at' => now()->subDays(rand(1, 10)),
                    'user_agent' => 'Mozilla/5.0 (Demo)',
                ]);
            }
        }
    }
}
