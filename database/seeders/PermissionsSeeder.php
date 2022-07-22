<?php

namespace Database\Seeders;

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
        DB::table('permissions')->truncate();
        DB::table('roles_permissions_join')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        //input all roles
        DB::table('roles')->insert([
            ['color' => '#8CC8FF', 'name' => 'admin', 'label' => 'administrators have all rights on the platform they are here to assist technician and users'],        //id 1
            ['color' => '#EF9D23', 'name' => 'technician', 'label' => 'technicians helps users by responding to tickets opened by users'],      //id 2
            ['color' => '#2b2d42', 'name' => 'user', 'label' => 'users are opening tickets to get helped by technicians'],  //id 3
        ]);

        //input all permissions
        DB::table('permissions')->insert([
            //  -------- TICKETS --------
            ['name' => 'create-ticket', 'label' => 'create a ticket'],           //id 1
            ['name' => 'update-ticket', 'label' => 'update a ticket'],           //id 2
            ['name' => 'read-ticket', 'label' => 'read a ticket'],               //id 3
            ['name' => 'delete-ticket', 'label' => 'delete a ticket'],           //id 4

            //  -------- CATEGORIES --------
            ['name' => 'create-category', 'label' => 'create a category'],       //id 5
            ['name' => 'update-category', 'label' => 'update a category'],       //id 6
            ['name' => 'read-category', 'label' => 'read a category'],           //id 7
            ['name' => 'delete-category', 'label' => 'delete a category'],       //id 8

            //  -------- STATUSES --------
            ['name' => 'create-status', 'label' => 'create a status'],           //id 9
            ['name' => 'update-status', 'label' => 'update a status'],           //id 10
            ['name' => 'read-status', 'label' => 'read a status'],               //id 11
            ['name' => 'delete-status', 'label' => 'delete a status'],           //id 12

            //  -------- USERS --------
            ['name' => 'create-user', 'label' => 'create a user'],               //id 13
            ['name' => 'update-user', 'label' => 'update a user'],               //id 14
            ['name' => 'read-user', 'label' => 'update a user'],                 //id 15
            ['name' => 'delete-user', 'label' => 'delete a user'],               //id 16

            //  -------- ROLES --------
            ['name' => 'create-role', 'label' => 'create a role'],               //id 17
            ['name' => 'update-role', 'label' => 'update a role'],               //id 18
            ['name' => 'read-role', 'label' => 'read a role'],                   //id 19
            ['name' => 'delete-role', 'label' => 'delete a role'],               //id 20

            //  -------- PERMISSIONS --------
            ['name' => 'create-permission', 'label' => 'create a permission'],   //id 21
            ['name' => 'update-permission', 'label' => 'update a permission'],   //id 22
            ['name' => 'read-permission', 'label' => 'read a permission'],       //id 23
            ['name' => 'delete-permission', 'label' => 'delete a permission'],   //id 24

            //  -------- DATA --------
            ['name' => 'read-data', 'label' => 'read a data'],                   //id 25
        ]);

        //link all roles and permissions
        DB::table('roles_permissions_join')->insert([
            // -------- ADMIN --------
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 1, 'permission_id' => 5],
            ['role_id' => 1, 'permission_id' => 6],
            ['role_id' => 1, 'permission_id' => 7],
            ['role_id' => 1, 'permission_id' => 8],
            ['role_id' => 1, 'permission_id' => 9],
            ['role_id' => 1, 'permission_id' => 10],
            ['role_id' => 1, 'permission_id' => 11],
            ['role_id' => 1, 'permission_id' => 12],
            ['role_id' => 1, 'permission_id' => 13],
            ['role_id' => 1, 'permission_id' => 14],
            ['role_id' => 1, 'permission_id' => 15],
            ['role_id' => 1, 'permission_id' => 16],
            ['role_id' => 1, 'permission_id' => 17],
            ['role_id' => 1, 'permission_id' => 18],
            ['role_id' => 1, 'permission_id' => 19],
            ['role_id' => 1, 'permission_id' => 20],
            ['role_id' => 1, 'permission_id' => 21],
            ['role_id' => 1, 'permission_id' => 22],
            ['role_id' => 1, 'permission_id' => 23],
            ['role_id' => 1, 'permission_id' => 24],
            ['role_id' => 1, 'permission_id' => 25],

            // -------- TECHNICIAN --------
            ['role_id' => 2, 'permission_id' => 1],
            ['role_id' => 2, 'permission_id' => 2],
            ['role_id' => 2, 'permission_id' => 3],
            ['role_id' => 2, 'permission_id' => 25],

            // -------- USER --------
            ['role_id' => 3, 'permission_id' => 1],
        ]);
    }
}
