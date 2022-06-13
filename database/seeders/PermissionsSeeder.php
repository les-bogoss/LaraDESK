<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //clear roles and permissions tables
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table("permissions")->truncate();
        DB::table("roles")->truncate();
        DB::table("roles_permissions_join")->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        //input all roles
        DB::table("roles")->insert([
            ["color" => "FFFFFF", "name" => "admin", "label" => "administrator"],        //id 1
            ["color" => "FFFFFF", "name" => "technician", "label" => "technician"],      //id 2
            ["color" => "FFFFFF", "name" => "user", "label" => "user of the platform"],  //id 3
        ]);

        //input all permissions
        DB::table("permissions")->insert([
            ["name" => "create-ticket", "label" => "create a ticket"],      //id 1
            ["name" => "edit-ticket", "label" => "edit a ticket"],          //id 2
            ["name" => "delete-ticket", "label" => "delete a ticket"],      //id 3
            ["name" => "create-category", "label" => "create a category"],  //id 4
            ["name" => "edit-category", "label" => "edit a category"],      //id 5
            ["name" => "delete-category", "label" => "delete a category"],  //id 6
            ["name" => "create-status", "label" => "create a status"],      //id 7
            ["name" => "edit-status", "label" => "edit a status"],          //id 8
            ["name" => "delete-status", "label" => "delete a status"],      //id 9
            ["name" => "create-user", "label" => "create a user"],          //id 10
            ["name" => "edit-user", "label" => "edit a user"],              //id 11
            ["name" => "delete-user", "label" => "delete a user"],          //id 12
        ]);

        //link all roles and permissions
        DB::table("roles_permissions_join")->insert([
            //administrator
            ["role_id" => 1, "permission_id" => 1],
            ["role_id" => 1, "permission_id" => 2],
            ["role_id" => 1, "permission_id" => 3],
            ["role_id" => 1, "permission_id" => 4],
            ["role_id" => 1, "permission_id" => 5],
            ["role_id" => 1, "permission_id" => 6],
            ["role_id" => 1, "permission_id" => 7],
            ["role_id" => 1, "permission_id" => 8],
            ["role_id" => 1, "permission_id" => 9],
            ["role_id" => 1, "permission_id" => 10],
            ["role_id" => 1, "permission_id" => 11],
            ["role_id" => 1, "permission_id" => 12],

            //technician
            ["role_id" => 2, "permission_id" => 1],
            ["role_id" => 2, "permission_id" => 2],
            ["role_id" => 2, "permission_id" => 3],
            ["role_id" => 2, "permission_id" => 4],
        ]);
    }
}