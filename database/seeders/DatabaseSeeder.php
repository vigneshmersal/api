<?php

namespace Database\Seeders;

use App\Models\Ticket;
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
        User::factory()->hasTickets(25)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(20)->hasTickets(5)->create();

        // Ticket::factory(50)->create();
    }
}
