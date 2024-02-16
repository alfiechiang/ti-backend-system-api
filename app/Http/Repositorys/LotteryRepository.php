<?php

namespace App\Http\Repositorys;

use App\Models\Lottery;
use App\Models\LotteryNumber;
use Exception;
use Illuminate\Support\Facades\Log;

class LotteryRepository
{
    public function pageList($data)
    {

        try {
            $Builder = new Lottery();
            if (!empty($data['filter'])) {
                $Builder = $Builder->where("type", $data['filter'])
                    ->orwhere("lottery_name", $data['filter']);
            }

            $res = $Builder->paginate($data['per_page']);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $res;
    }


    public function findOne($type)
    {
        try {
            $lottery = Lottery::where("type", $type)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $lottery;
    }

    public function list()
    {
        try {
            $lists = Lottery::get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $lists;
    }
    
    public function edit($type)
    {
        try {
            $lottery = Lottery::where("type", $type)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $lottery;
    }

    public function update($data,$type)
    {

        try {
            $lottery = Lottery::where("type", $type)->first();
            $lottery->open = $data['open'];
            $lottery->bet_time = $data['bet_time'];
            $lottery->lottery_name = $data['lottery_name'];
            $lottery->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

    }

    public function periodList($data){
        try {
            $Builder = new LotteryNumber();
            if (!empty($data['period'])) {
                $Builder = $Builder->where("period", $data['period']);
            }

            if (!empty($data['type'])) {
                $Builder = $Builder->where("type", $data['type']);
            }

            $res = $Builder->orderBy("open_time",'desc')->paginate($data['per_page']);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $res;

    }

    public function periodBatchUpdate($data){
        try {
            foreach ($data as $area) {
                $stake = LotteryNumber::where("period", $area['period'])->where("type", $area['type'])->first();
                $stake->numbers = $area['numbers'];
                $stake->open_time = $area['open_time'];
                $stake->save();
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
