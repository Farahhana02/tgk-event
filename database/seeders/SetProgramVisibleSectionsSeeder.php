<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class SetProgramVisibleSectionsSeeder extends Seeder
{
    public function run()
    {
        $programs = Program::all();

        foreach ($programs as $program) {
            // Only update if visible_sections is null
            if ($program->visible_sections === null) {
                $program->visible_sections = [
                    'overview' => true,
                    'tentative' => true,
                    'vip' => true,
                    'participation' => true,
                    'sponsorship' => true,
                    'programme' => true
                ];
                $program->save();
            }
        }

        $this->command->info('Updated ' . $programs->count() . ' programs with default visible sections.');
    }
}