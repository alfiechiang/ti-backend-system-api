<?php

namespace Database\Seeders;

use App\Models\AgentFs;
use App\Models\Coin;
use App\Models\Commercial;
use App\Models\CompanyFs;
use App\Models\Game;
use App\Models\Hierarchy;
use App\Models\LotteryPeriod;
use App\Models\Machine;
use App\Models\Member;
use App\Models\MemberFs;
use App\Models\MemberGameStatus;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\SlotReocrd;
use App\Models\StationFs;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::truncate();
        User::create(["account" => "admin", "password" => bcrypt("aa1234"), "role_id" => 1, "supervisor" => true]);
        Hierarchy::truncate();
        Member::truncate();
        CompanyFs::truncate();
        StationFs::truncate();
        AgentFs::truncate();
        MemberFs::truncate();
        Machine::truncate();
        Role::truncate();
        Menu::truncate();
        Coin::truncate();
        RoleMenu::truncate();
        Commercial::truncate();
        SlotReocrd::truncate();
        MemberGameStatus::truncate();
        $this->call([
            GameSeeder::class,
            MachineSeeder::class,
            RoleSeeder::class,
            HierarchySeeder::class,
            MenuSeeder::class,
            RoleMenuSeeder::class,
            CoinSeeder::class,
            CommercialSeeder::class,
            CoinEventSeeder::class,
            PlayerCoinEvent::class
        ]);
    }


   
}
