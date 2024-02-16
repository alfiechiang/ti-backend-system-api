<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\PlayerCoinEvent as ModelsPlayerCoinEvent;
use Illuminate\Database\Seeder;

class PlayerCoinEvent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ModelsPlayerCoinEvent::truncate();

        $members = Member::all();
        foreach ($members as $member) {
            ModelsPlayerCoinEvent::create(
                ["member_id" => $member->id, "coin_name" => "VIETNAM_COIN", "coin_daily" => 500, "coin" => 500, "stop" => false]
            );
            ModelsPlayerCoinEvent::create(
                ["member_id" => $member->id, "coin_name" => "KOREA_COIN", "coin_daily" => 500, "coin" => 500, "stop" => false]
            );
        }
    }
}
