<?php

namespace App\Http\Repositorys;

use App\Models\LotteryOdd;
use App\Models\PickNumLimit;
use App\Models\PickNumOdd;
use App\Models\Stake;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakeRepository
{

    public function list($data)
    {

        try {
            $res = [];

           $postion= DB::table("lottery_odds")
                ->leftJoin("stakes", "lottery_odds.stake_id", "=", "stakes.stake_id")
                ->where("type", $data['type'])
                ->select("lottery_odds.*","stakes.stake_name")
                ->get();
            $res['postion'] = $postion;

            $option = PickNumLimit::where("type", $data['type'])->get();
            $res['option'] = $option;
            $zhung = PickNumOdd::where("type", $data['type'])->get();
            $res['zhung'] = $zhung;
                
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }


        return $res;
    }

    public function batchUpdate($data)
    {

        try {
            $postions = $data['postion'];
            foreach ($postions as $positioin) {
                $stake = LotteryOdd::where("type", $positioin['type'])->where("stake_id", $positioin['stake_id'])->first();
                $stake->red_limit = $positioin['red_limit'];
                $stake->odds = $positioin['odds'];
                $stake->save();
            }

            $options = $data['option'];
            foreach ($options as $option) {
                $pickNumLimit = PickNumLimit::where("type", $option['type'])->where("option", $option['option'])->first();
                $pickNumLimit->red_limit = $option['red_limit'];
                $pickNumLimit->save();
            }

            $zhungs = $data['zhung'];
            foreach ($zhungs as $zhung) {
                $pickNumOdd = PickNumOdd::where("type", $zhung['type'])
                ->where("option", $zhung['option'])->where("zhung", $zhung['zhung'])->first();
                $pickNumOdd->odds = $zhung['odds'];
                $pickNumOdd->save();
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
