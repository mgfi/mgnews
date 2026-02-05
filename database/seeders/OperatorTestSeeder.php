<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OperatorTestSeeder extends Seeder
{
    public function run(): void
    {
        // ACTIVE OPERATORS
        User::factory()->count(3)->create([
            'utype'      => User::TYPE_USER,
            'is_active'  => true,
        ]);

        // INACTIVE OPERATORS
        User::factory()->count(2)->create([
            'utype'      => User::TYPE_USER,
            'is_active'  => false,
        ]);

        // SOFT DELETED OPERATORS
        User::factory()->count(2)->create([
            'utype'     => User::TYPE_USER,
            'is_active' => false,
        ])->each(function ($user) {
            $user->delete();
        });
    }
}
