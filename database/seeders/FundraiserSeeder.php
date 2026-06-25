<?php

namespace Database\Seeders;

use App\Models\Fundraiser;
use Illuminate\Database\Seeder;

class FundraiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample fundraisers for testing
        $fundraisers = [
            [
                'programme_name' => 'Young Talents Development Program',
                'start_date' => '2025-01-01',
                'end_date' => '2026-01-31',
                'target_amount' => 10000.00,
                'progress' => 0.00,
                'description' => 'Supporting young talents in Kedah with education and training programs.',
                'status' => 'active',
            ],
            [
                'programme_name' => 'Community Development Fund',
                'start_date' => '2025-03-01',
                'end_date' => '2025-12-31',
                'target_amount' => 25000.00,
                'progress' => 0.00,
                'description' => 'Building stronger communities through infrastructure and social programs.',
                'status' => 'active',
            ],
        ];

        foreach ($fundraisers as $fundraiser) {
            Fundraiser::create($fundraiser);
        }

        $this->command->info('✓ Sample fundraisers created (2 programs)');
    }
}
