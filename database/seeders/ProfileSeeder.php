<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Andreas Trölss',
                'title' => 'Fullstack-utvecklare',
                'bio' => 'Erfaren webbutvecklare specialiserad på Laravel och moderna frontend-ramverk. Jag hjälper företag att bygga skalbara och användarvänliga webbapplikationer från idé till färdig produkt.',
                'email' => 'info@atdev.me',
                'github' => 'https://github.com',
                'linkedin' => 'https://linkedin.com',
            ]
        );

        $this->command->info('Profile created');
    }
}
