<?php

namespace Database\Seeders;

use App\Models\Fine;
use App\Models\User;
use App\Models\Borrow;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample fines for demonstration
        $users = User::where('user_type', 'user')->limit(3)->get();

        foreach ($users as $user) {
            // Create an unpaid fine
            Fine::create([
                'user_id' => $user->id,
                'amount' => 5.00,
                'reason' => 'Late book return - 5 days overdue',
                'status' => 'unpaid',
                'amount_paid' => 0,
                'due_date' => now()->addDays(7),
            ]);

            // Create a partial fine
            Fine::create([
                'user_id' => $user->id,
                'amount' => 10.00,
                'reason' => 'Damaged book cover',
                'status' => 'partial',
                'amount_paid' => 5.00,
                'due_date' => now()->addDays(14),
            ]);
        }
    }
}
