<?php

namespace Database\Seeders;

use App\Models\AwardProgram;
use Illuminate\Database\Seeder;

class AwardProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Kedah Innovation Award 2025
        $kia2025 = AwardProgram::create([
            'title' => 'Kedah Innovation Award 2025',
            'description' => 'The premier innovation award recognizing excellence in entrepreneurship, technology, and community development in Kedah.',
        ]);

        // Sections are auto-created by the AwardProgram model (6 sections)
        // Update Overview section with sample content
        $overview = $kia2025->contents()->where('section_title', 'Overview')->first();
        if ($overview) {
            $overview->update([
                'section_body' => '<h2>About Kedah Innovation Award 2025</h2>
                <p>The Kedah Innovation Award celebrates outstanding achievements in innovation, entrepreneurship, and community impact.</p>
                <ul>
                    <li>Recognition for innovative businesses</li>
                    <li>Networking opportunities with industry leaders</li>
                    <li>Prize pool and sponsorship packages</li>
                    <li>Media coverage and publicity</li>
                </ul>',
                'is_visible' => true,
            ]);
        }

        // Update Programme Tentative section
        $tentative = $kia2025->contents()->where('section_title', 'Programme Tentative')->first();
        if ($tentative) {
            $tentative->update([
                'section_body' => '<h3>Event Schedule</h3>
                <ul>
                    <li><strong>6:00 PM</strong> - Registration & Welcome Reception</li>
                    <li><strong>7:00 PM</strong> - Opening Ceremony</li>
                    <li><strong>7:30 PM</strong> - Gala Dinner</li>
                    <li><strong>8:30 PM</strong> - Award Presentation</li>
                    <li><strong>9:30 PM</strong> - Networking Session</li>
                    <li><strong>10:00 PM</strong> - Closing & Thank You</li>
                </ul>',
                'is_visible' => true,
            ]);
        }

        $this->command->info('✓ Sample award program created: Kedah Innovation Award 2025');
        $this->command->info('  → Auto-created 6 sections:');
        $this->command->info('     • Overview (with content)');
        $this->command->info('     • Programme Tentative (with content)');
        $this->command->info('     • VIP');
        $this->command->info('     • Participation');
        $this->command->info('     • Sponsorship');
        $this->command->info('     • Programme');
    }
}