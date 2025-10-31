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
            ['email' => 'andreas.thun@gmail.com'],
            [
                'name' => 'Andreas Thun',
                'password' => Hash::make('119133998'),
            ]
        );

        $this->command->info('Admin user created: andreas.thun@gmail.com');
    }
}
