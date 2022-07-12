<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //seed roles and permissions
        $this->call(PermissionsSeeder::class);

        //seed tickets categories and statuses
        $this->call(TicketsSeeder::class);

        //if the current env is not production, seed the database with fake data
        if (env('APP_ENV') !== 'production') {
            User::factory()->count(5)->create();
            Ticket::factory()->count(50)->create();
        }
    }
}
