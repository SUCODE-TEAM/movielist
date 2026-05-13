<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample user
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@movielist.test',
            'password' => bcrypt('password123'),
            'bio' => 'Pecinta film sejati yang suka menonton berbagai genre',
        ]);

        User::factory(5)->create();
    }
}
