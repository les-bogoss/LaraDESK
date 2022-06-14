<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //clear categories and statuses tables
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table("ticket_status")->truncate();
        DB::table("ticket_categories")->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        //input all categories
        DB::table("ticket_categories")->insert([
            ["name" => "1", "label" => "label1"],
            ["name" => "2", "label" => "label2"],
            ["name" => "3", "label" => "label3"],
        ]);

        //input all statuses
        DB::table("ticket_status")->insert([
            ["name" => "OUVERT", "label" => "ticket ouvert mais pas encore assigné"],
            ["name" => "ATTRIBUÉ", "label" => "ticket attribué a un technicien"],
            ["name" => "ATTENTE REPONSE", "label" => "ticket en attente de réponse"],
            ["name" => "RÉSOLU", "label" => "ticket résolu"],
            ["name" => "CLOS", "label" => "ticket clos"],
        ]);
    }
}
