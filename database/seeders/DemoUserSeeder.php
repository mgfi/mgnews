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
        if (User::where('email', 'mgfirmowy@gmail.com')->exists()) {
            return;
        }

        User::create([
            'name'              => 'Test Admin',
            'email'             => 'mgfirmowy@gmail.com',
            'password'          => Hash::make('admin1234'),
            'utype'             => 'ADM',
            'email_verified_at' => now(), // na razie verified, testowo
            'must_change_password' => true, // DOBRA PRAKTYKA
        ]);
    }
}
