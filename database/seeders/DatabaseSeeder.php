<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ADMIN
        User::updateOrCreate(
            ['email' => 'admin@admin.pl'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin1234'),
                'utype' => 'ADM',
            ]
        );

        // USER / CUSTOMER
        User::updateOrCreate(
            ['email' => 'user@user.pl'],
            [
                'name' => 'User',
                'password' => Hash::make('user1234'),
                'utype' => 'USR',
            ]
        );
    }
}
