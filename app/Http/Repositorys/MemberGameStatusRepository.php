<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Game;
use App\Models\Hierarchy;
use App\Models\MemberGameStatus;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberGameStatusRepository
{

    public  function create($data)
    {
        # code...
        try {

            if (isset($data['slot_open'])){
                $slot = Game::where("game_type", Constant::SLOT_GAMETYPE)->first();
                MemberGameStatus::create([
                    "game_type" => $slot->game_type,
                    "game_name" => $slot->name,
                    "member_id" => $data['member_id'],
                    "open" => $data['slot_open']
                ]);

            }


            if (isset($data['lottery_open'])){
                $lottery = Game::where("game_type", Constant::LOTTERY_GAMETYPE)->first();
                MemberGameStatus::create([
                    "game_type" => $lottery->game_type,
                    "game_name" => $lottery->name,
                    "member_id" => $data['member_id'],
                    "open" => $data['lottery_open']
                ]);

            }

            if (isset($data['bt_open'])){
                $bt = Game::where("game_type", Constant::BT_GAMETYPE)->first();
                MemberGameStatus::create([
                    "game_type" => $bt->game_type,
                    "game_name" => $bt->name,
                    "member_id" => $data['member_id'],
                    "open" => $data['bt_open']
                ]);

            }

          

            

          
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function update($games, $member_id)
    {
        foreach ($games as $game) {
            $model = MemberGameStatus::where("game_type", $game['game_type'])->where("member_id", $member_id)->first();
            $model->open = $game['open'];
            $model->save();

        }
    }
}
