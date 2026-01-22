<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::updateOrCreate(
            [
                'email' => 'mgfirmowy@gmail.com',
            ],
            [
                'name'               => 'Test Admin',
                'password'           => Hash::make('admin1234'),
                'utype'              => User::TYPE_ADMIN,
                'permissions'        => [], // admin ma bypass
                'is_active'          => true,
                'email_verified_at'  => now(),
            ]
        );

        // OPERATOR (USR)
        User::updateOrCreate(
            [
                'email' => 'user@user.pl',
            ],
            [
                'name'               => 'Test Operator',
                'password'           => Hash::make('user1234'),
                'utype'              => User::TYPE_USER,
                'permissions'        => [
                    'view_dashboard',
                    'subscriber_view',
                    'newsletter_view',
                ],
                'is_active'          => true,
                'email_verified_at'  => now(),
            ]
        );
    }
}
