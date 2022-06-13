<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Ticket_category;
use App\Models\Ticket_status;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            Ticket::factory()->count(10)->create();
        }
    }
}
