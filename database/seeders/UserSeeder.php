<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingUser = User::where('email', 'admin@kedahforward.com')->first();
        
        if (!$existingUser) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@kedahforward.com',
                'password' => Hash::make('admin1212'),
            ]);

            $this->command->info('✓ Admin user created: admin@kedahforward.com / admin1212');
        } else {
            $this->command->warn('⚠ Admin user already exists, skipping...');
        }
    }
}
