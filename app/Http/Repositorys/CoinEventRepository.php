<?php

namespace App\Http\Repositorys;

use App\Models\CoinEvent;
use App\Models\Member;
use App\Models\PlayerCoinEvent;
use Exception;
use Illuminate\Support\Facades\Log;

class CoinEventRepository
{

    public function member_edit($member_id)
    {

        try {
            $event = PlayerCoinEvent::where("member_id", $member_id)->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $event;
    }

    public function member_update($member_id, $data)
    {

        try {
            $event = PlayerCoinEvent::where("member_id", $member_id)->where("coin_name", $data['coin_name'])->first();
            $event->stop = $data['stop'];
            $event->coin_daily = $data['coin_daily'];
            $event->coin = $data['coin'];
            $event->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $event;
    }

    public function member_default_add($member_id)
    {

        try {

            PlayerCoinEvent::create(
                ["member_id" => $member_id, "coin_name" => "VIETNAM_COIN", "coin_daily" => 500, "coin" => 500, "stop" => false]
            );
            PlayerCoinEvent::create(
                ["member_id" => $member_id, "coin_name" => "KOREA_COIN", "coin_daily" => 500, "coin" => 500, "stop" => false]
            );
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function member_balance($account, $coin_name, $balance)
    {
        $member = Member::where("account", $account)->first();
        $member_id = $member->id;
        $event = PlayerCoinEvent::where("member_id", $member_id)->where("coin_name", $coin_name)->first();
        $event->coin += $balance;
        if ($event->coin < 0) {
            $event->coin = 0;
        }
        $event->save();
        return $event->coin;
    }
}
