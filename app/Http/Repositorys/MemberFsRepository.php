<?php

namespace App\Http\Repositorys;

use App\Models\Game;
use App\Models\LotteryRecord;
use App\Models\Member;
use App\Models\MemberFs;
use App\Models\SlotReocrd;

class MemberFsRepository
{


    public function btCaculate($date)
    {

        $start_time = $date . " 00:00:00";
        $end_time = $date . " 23:59:59";
        $records = SlotReocrd::selectRaw("account,SUM(result) as result")
            ->where("created_at", ">=", $start_time)
            ->where("created_at", "<=", $end_time)
            ->where("caculate", true)
            ->where("status", "!=", 2)
            ->where("type", "BT")
            ->groupBy("account")->get();
        //利用relations 查找會員父級帳號
        $relations = $this->getBTParentLelation($start_time, $end_time);
        $arrays = $records->toArray();

        $game = Game::where("name", "bt")->first();
        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game->game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $InsertData[] = $m;
        }

        MemberFs::insert($InsertData);
    }


    private function getBTParentLelation($start_time, $end_time)
    {

        $plucked = SlotReocrd::where("created_at", ">=", $start_time)
            ->where("type", "BT")

            ->where("created_at", "<=", $end_time)->pluck("account");
        $users = $plucked->all();

        $relations = (Member::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }



    public function slotCaculate($date)
    {


        $start_time = $date . " 00:00:00";
        $end_time = $date . " 23:59:59";
        $records = SlotReocrd::selectRaw("account,SUM(result) as result")
            ->where("created_at", ">=", $start_time)
            ->where("created_at", "<=", $end_time)
            ->where("caculate", true)
            ->where("type", "SLOT")
            ->where("status", "!=", 2)
            ->groupBy("account")->get();
        //利用relations 查找會員父級帳號
        $relations = $this->getSlotParentLelation($start_time, $end_time);
        $arrays = $records->toArray();

        $game = Game::where("name", "slot")->first();
        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game->game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $InsertData[] = $m;
        }

        MemberFs::insert($InsertData);
    }


    private function getSlotParentLelation($start_time, $end_time)
    {

        $plucked = SlotReocrd::where("created_at", ">=", $start_time)
            ->where("type", "SLOT")
            ->where("created_at", "<=", $end_time)->pluck("account");
        $users = $plucked->all();

        $relations = (Member::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }

    public function lotteryCaculate($date)
    {
        $start_time = $date . " 00:00:00";
        $end_time = $date . " 23:59:59";
        $selectRaw=" account,SUM(bet_money) as total_bet,SUM(bet_money) as valid_bet,
        SUM(result) as result";
        $records = LotteryRecord::selectRaw($selectRaw)
            ->where("bet_time", ">=", $start_time)
            ->where("bet_time", "<=", $end_time)
            ->where("status", "!=", 2)
            ->where("caculate", true)
            ->groupBy("account")->get();

        //利用relations 查找會員父級帳號
        $relations = $this->getLotteryParentLelation($start_time, $end_time);
        $arrays = $records->toArray();
        $game = Game::where("name", "lottery")->first();
        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game->game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $InsertData[] = $m;
        }


        MemberFs::insert($InsertData);
    }

    private function getLotteryParentLelation($start_time, $end_time)
    {

        $plucked = LotteryRecord::where("bet_time", ">=", $start_time)
            ->where("bet_time", "<=", $end_time)->pluck("account");
        $users = $plucked->all();

        $relations = (Member::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }
}
