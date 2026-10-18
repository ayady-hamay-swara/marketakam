<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'Ayad'],
            [
                'current_store_id' => 1,
                'email' => 'ayad@marketakam.local',
                'password' => Hash::make('Ayad1234'),
                'role' => 'super_dev',
            ]
        );

        User::updateOrCreate(
            ['username' => 'owner1'],
            [
                'current_store_id' => 1,
                'email' => 'owner@marketakam.local',
                'password' => Hash::make('Ayad1234'),
                'role' => 'owner',
            ]
        );

        $cashierAssignments = [
            'cashier1' => 1,
            'cashier2' => 2,
            'cashier3' => 3,
            'cashier4' => 4,
        ];

        foreach ($cashierAssignments as $username => $storeId) {
            User::updateOrCreate(
                ['username' => $username],
                [
                    'current_store_id' => $storeId,
                    'email' => $username . '@marketakam.local',
                    'password' => Hash::make('Ayad1234'),
                    'role' => 'cashier',
                ]
            );
        }
    }
}
