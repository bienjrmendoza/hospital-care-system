<?php

namespace Database\Seeders;

use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitialAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@hospital.test'],
            [
                'name' => 'Initial Admin',
                'password' => 'admin12345',
                'role' => User::ROLE_ADMIN,
            ]
        );

        foreach (['General Medicine', 'Cardiology', 'Pediatrics', 'Orthopedics'] as $name) {
            Specialization::query()->firstOrCreate(['name' => $name]);
        }
    }
}
