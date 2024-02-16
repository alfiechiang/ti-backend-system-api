<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Member;
use App\Models\MemberGameStatus;
use Illuminate\Database\Seeder;

class MemberGameStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        MemberGameStatus::truncate();
        $members = Member::all();
        $games = Game::all();
        foreach ($members as $member){
            foreach ($games as $game) {

                MemberGameStatus::create([
                    "game_type" => $game->game_type,
                    "game_name" => $game->name,
                    "member_id" => $member->id,
                    "open" => 1
                ]);
            }

        }
    }
}
