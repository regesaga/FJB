<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'admin@fjb.id',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Kuningan1994'),
            ]
        );

        $user->assignRole('Super Admin');
    }
}