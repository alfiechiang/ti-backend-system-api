<?php

namespace Database\Seeders;

use App\Models\RoleMenu;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RoleMenu::truncate();
        $role_menus = [

            ["role_id" => 1, "menu_id" => 1],
            ["role_id" => 1, "menu_id" => 5],
            ["role_id" => 1, "menu_id" => 6],
            ["role_id" => 1, "menu_id" => 7],
            ["role_id" => 1, "menu_id" => 8],
            ["role_id" => 1, "menu_id" => 12],

            ["role_id" => 2, "menu_id" => 1],
            ["role_id" => 2, "menu_id" => 6],
            ["role_id" => 2, "menu_id" => 7],
            ["role_id" => 2, "menu_id" => 8],
            ["role_id" => 1, "menu_id" => 12],

            ["role_id" => 3, "menu_id" => 1],
            ["role_id" => 3, "menu_id" => 7],
            ["role_id" => 3, "menu_id" => 8],
            ["role_id" => 3, "menu_id" => 12],

        ];
        RoleMenu::insert($role_menus);
    }
}
