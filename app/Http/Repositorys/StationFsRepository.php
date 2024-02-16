<?php

namespace App\Http\Repositorys;

use App\Models\AgentFs;
use App\Models\Game;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\MemberFs;
use App\Models\SlotReocrd;
use App\Models\StationFs;

class StationFsRepository
{


    public function btCaculate($date)
    {

        $game = Game::where("name", "bt")->first();
        $game_type = $game->game_type;

        $records = AgentFs::selectRaw("parent_account as account,SUM(result) as result,SUM(uplevel_occupy_money	) as occupy_money")
            ->where("date", $date)
            ->where("game_type",$game_type)
            ->groupBy("parent_account")->get();
        //利用relations 查找站長父級帳號 && 佔成比例
        $relations = $this->getBTLelation($date,$game_type);
        $arrays = $records->toArray();


        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $m['uplevel_occupy_money'] = $m['occupy_money'] * (1 - $relations[$account]->occupy_percent / 100);
            $m['occupy_money'] = $m['occupy_money'] * ($relations[$account]->occupy_percent / 100);
            $InsertData[] = $m;
        }


        StationFs::insert($InsertData);
    }

    private function getBTLelation($date,$game_type)
    {

        $plucked = AgentFs::where("date", $date)
            ->with('upLevel')
            ->where("game_type",$game_type)
            ->pluck("parent_account");
        $users = $plucked->all();

        $relations = (Hierarchy::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }

    public function slotCaculate($date)
    {

        $game = Game::where("name", "slot")->first();
        $game_type = $game->game_type;


        $records = AgentFs::selectRaw("parent_account as account,SUM(result) as result,SUM(uplevel_occupy_money	) as occupy_money")
            ->where("date", $date)
            ->where("game_type",$game_type)
            ->groupBy("parent_account")->get();
        //利用relations 查找站長父級帳號 && 佔成比例
        $relations = $this->getSlotLelation($date,$game_type);
        $arrays = $records->toArray();


        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $m['uplevel_occupy_money'] = $m['occupy_money'] * (1 - $relations[$account]->occupy_percent / 100);
            $m['occupy_money'] = $m['occupy_money'] * ($relations[$account]->occupy_percent / 100);
            $InsertData[] = $m;
        }


        StationFs::insert($InsertData);
    }


    private function getSlotLelation($date,$game_type)
    {

        $plucked = AgentFs::where("date", $date)
            ->with('upLevel')
            ->where("game_type",$game_type)
            ->pluck("parent_account");
        $users = $plucked->all();

        $relations = (Hierarchy::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }

    public function lotteryCaculate($date)
    {
        $game = Game::where("name", "lottery")->first();
        $game_type = $game->game_type;

        $selectRaw="parent_account as account,SUM(result) as result,SUM(uplevel_occupy_money) as occupy_money,
        SUM(total_bet) as valid_bet,SUM(total_bet) as total_bet";

        $records = AgentFs::selectRaw($selectRaw)
            ->where("date", $date)
            ->where("game_type",$game_type)
            ->groupBy("parent_account")->get();
        //利用relations 查找站長父級帳號 && 佔成比例
        $relations = $this->getLotteryLelation($date,$game_type);
        $arrays = $records->toArray();

        $InsertData = [];
        foreach ($arrays as $m) {
            $account = $m['account'];
            $m['parent_account'] = $relations[$account]->upLevel->account;
            $m['game_type'] = $game_type;
            $m['game_id'] = 0;
            $m['date'] = $date;
            $m['uplevel_occupy_money'] = $m['occupy_money'] * (1 - $relations[$account]->occupy_percent / 100);
            $m['occupy_money'] = $m['occupy_money'] * ($relations[$account]->occupy_percent / 100);
            $InsertData[] = $m;
        }

        StationFs::insert($InsertData);

    }
    private function getLotteryLelation($date,$game_type)
    {

        $plucked = AgentFs::where("date", $date)
            ->with('upLevel')
            ->where("game_type",$game_type)
            ->pluck("parent_account");
        $users = $plucked->all();

        $relations = (Hierarchy::whereIn("account", $users)->with("upLevel")->get())->keyBy("account");
        return $relations;
    }
}
