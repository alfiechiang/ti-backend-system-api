<?php
namespace App\Http\Repositorys;

use App\Models\PickNumOdd;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LotteryOddRepository
{



    //該彩票注區 賠率限紅列表
    public function typeList($type){

        $res= DB::table("lottery_odds")
        ->leftJoin("stakes", "lottery_odds.stake_id", "=", "stakes.stake_id")
        ->where("type", $type)
        ->select("lottery_odds.*","stakes.stake_name")
        ->get();

        return $res;

    }

    //猜中選號賠率
    public function optionList($type){

        try {
            $res =PickNumOdd::where("type",$type)->get();

        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $res;

    }


}
