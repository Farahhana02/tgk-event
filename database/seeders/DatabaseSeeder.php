<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call individual seeders in order
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            // Uncomment below if you want sample data for testing
            // FundraiserSeeder::class,
            // AwardProgramSeeder::class,
        ]);

        $this->command->info('✓ Database seeding completed successfully!');
    }
}
