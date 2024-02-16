<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["name" => "公司"],
            ["name" => "站長"],
            ["name" => "代理"],
            ["name" => "會員"]
        ];
        Role::insert($roles);
    }
}
