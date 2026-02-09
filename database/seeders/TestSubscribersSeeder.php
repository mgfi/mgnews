<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestSubscribersSeeder extends Seeder
{
    /**
     * ⚠️ DEV / TEST ONLY
     * Seeder do testów UI / paginacji
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->warn('⛔ TestSubscribersSeeder blocked in production');
            return;
        }

        // ❗ BEZ TRUNCATE (FK SAFE)
        Subscriber::query()->delete();

        // (opcjonalnie) reset auto_increment
        DB::statement('ALTER TABLE subscribers AUTO_INCREMENT = 1');

        $rows = [];

        for ($i = 1; $i <= 200; $i++) {
            $rows[] = [
                'email'             => "test-subscriber{$i}@example.test",
                'is_active'         => random_int(0, 1),
                'source'            => 'test-seeder',
                'unsubscribe_token' => Str::uuid(),
                'created_at'        => now()->subDays(random_int(0, 365)),
                'updated_at'        => now(),
            ];
        }

        Subscriber::insert($rows);

        $this->command->info('✅ 200 TEST subscribers seeded (FK-safe)');
    }
}
