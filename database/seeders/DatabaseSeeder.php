<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test regular user
        User::create([
            'username' => 'john_reader',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'user',
            'membership' => true,
        ]);

        // Create a test librarian
        User::create([
            'username' => 'alice_librarian',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'librarian',
            'membership' => true,
        ]);

        // Create additional test users
        User::factory(5)->create();
    }
}
