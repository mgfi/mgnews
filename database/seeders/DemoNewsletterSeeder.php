<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NewsletterIssue;

class DemoNewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jeśli demo newslettery już istnieją — nie twórz ponownie
        if (NewsletterIssue::where('is_demo', true)->exists()) {
            return;
        }

        $admin = User::where('utype', 'ADM')->first();

        if (!$admin) {
            return;
        }

        // Newsletter WYSŁANY (do statystyk)
        NewsletterIssue::create([
            'title_pl' => 'Witaj w naszym newsletterze',
            'preview_text_pl' => 'To jest przykładowy newsletter DEMO',
            'slug_pl' => 'witaj-w-newsletterze',

            'content_html' => '<p>Dziękujemy za zapisanie się do naszego newslettera!</p>',
            'blocks_count' => 1,

            'status' => 'sent',
            'sent_at' => now()->subDays(14),

            'created_by' => $admin->id,
            'is_demo' => true,
        ]);

        // Newsletter ROBOCZY (draft)
        NewsletterIssue::create([
            'title_pl' => 'Zapowiedź nowych funkcji',
            'preview_text_pl' => 'Już wkrótce – zobacz co planujemy',

            'status' => 'draft',

            'created_by' => $admin->id,
            'is_demo' => true,
        ]);
    }
}
