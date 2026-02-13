<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\NewsletterIssue;
use App\Models\User;

class TestCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * ⚠️ TEST DATA ONLY – DO NOT USE IN PRODUCTION
     */
    public function run(): void
    {
        // === ADMIN USER (AUTHOR) ===
        $admin = User::first();

        if (! $admin) {
            $this->command->warn('Brak użytkownika – pomijam TestCampaignSeeder.');
            return;
        }

        // === TEST CAMPAIGNS ===
        $spring = Campaign::create([
            'title'        => '[TEST] Trendy Wiosna 2026',
            'is_active'    => true,
            'last_sent_at' => now()->subDays(5),
        ]);

        $bf = Campaign::create([
            'title'        => '[TEST] Black Friday 2025',
            'is_active'    => false,
            'last_sent_at' => now()->subMonths(3),
        ]);

        // === TEST NEWSLETTERS ===
        NewsletterIssue::create([
            'campaign_id'     => $spring->id,
            'title_pl'        => '[TEST] Nowa kolekcja wiosna',
            'preview_text_pl' => 'Zobacz trendy na wiosnę 2026',
            'status'          => 'sent',
            'sent_at'         => now()->subDays(5),
            'content_json'    => [],
            'created_by'      => $admin->id,
        ]);

        NewsletterIssue::create([
            'campaign_id'     => $spring->id,
            'title_pl'        => '[TEST] Kolory sezonu',
            'preview_text_pl' => 'Najmodniejsze kolory tej wiosny',
            'status'          => 'draft',
            'content_json'    => [],
            'created_by'      => $admin->id,
        ]);

        NewsletterIssue::create([
            'campaign_id'     => $bf->id,
            'title_pl'        => '[TEST] Zapowiedź Black Friday',
            'preview_text_pl' => 'Największe rabaty roku',
            'status'          => 'sent',
            'sent_at'         => now()->subMonths(3),
            'content_json'    => [],
            'created_by'      => $admin->id,
        ]);

        NewsletterIssue::create([
            'campaign_id'     => null,
            'title_pl'        => '[TEST] Promocja weekendowa',
            'preview_text_pl' => 'Tylko do niedzieli',
            'status'          => 'draft',
            'content_json'    => [],
            'created_by'      => $admin->id,
        ]);

        $this->command->info('✅ TestCampaignSeeder: kampanie i newslettery testowe dodane.');
    }
}
