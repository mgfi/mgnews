<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nie twórz ponownie jeśli już istnieje
        if (User::where('email', 'admin@demo.pl')->exists()) {
            return;
        }

        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.pl',
            'password' => Hash::make('password'),
            'utype' => 'ADM',
            'email_verified_at' => now(),
        ]);
    }
}
