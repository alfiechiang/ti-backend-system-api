<?php

namespace Database\Seeders;

use App\Models\MemberFs;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Menu::truncate();
        $menus = [
            ["name" => "登入密碼修改", "url" => "admin/password"],
            ["name" => "幣別設置", "url" => "admin/coin"],
            ["name" => "公告設置", "url" => "admin/notify"],
            ["name" => "分公司設置", "url" => "admin/company"],
            ["name" => "站長設置", "url" => "admin/station"],
            ["name" => "代理設置", "url" => "admin/agent"],
            ["name" => "會員設置", "url" => "admin/member"],
            ["name" => "遊戲報表查詢", "url" => "admin/settle/company"],
            ["name" => "電子機台管理", "url" => "admin/machine"],
            ["name" => "彩票遊戲管理", "url" => "admin/lottery"],
            ["name" => "公告設置", "url" => "admin/notify"],
            ["name" => "會員注單查詢", "url" => "admin/bet_records"],

        ];
        Menu::insert($menus);
    }
}
