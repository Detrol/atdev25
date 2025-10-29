<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@atdev.me'],
            [
                'name' => 'ATDev Admin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('Admin user created: admin@atdev.me / password');
    }
}
