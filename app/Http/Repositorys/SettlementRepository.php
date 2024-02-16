<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Http\Paginator;
use App\Models\AgentFs;
use App\Models\CompanyFs;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\MemberFs;
use App\Models\StationFs;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettlementRepository
{

    private function BuilderFilter($Builders, $data)
    {

        $Builder = $Builders['builder'];
        $SlotBuilder = $Builders['slot'];
        $LotteryBuilder = $Builders['lottery'];
        $BTBuilder = $Builders['bt'];


        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $startdate = Carbon::parse($data['starttime'])->format('Y-m-d');
            $enddate = Carbon::parse($data['endtime'])->format('Y-m-d');
            $Builder = $Builder->where("date", ">=", $startdate)
                ->where("date", "<=", $enddate);
            $SlotBuilder = $SlotBuilder->where("date", ">=", $startdate)
                ->where("date", "<=", $enddate);
            $LotteryBuilder = $LotteryBuilder->where("date", ">=", $startdate)
                ->where("date", "<=", $enddate);

            $BTBuilder = $BTBuilder->where("date", ">=", $startdate)
                ->where("date", "<=", $enddate);
        }

        if (isset($data['parent_account'])) {
            $Builder = $Builder->whereIn("parent_account", $data['parent_account']);
            $SlotBuilder = $SlotBuilder->whereIn("parent_account", $data['parent_account']);
            $LotteryBuilder = $LotteryBuilder->whereIn("parent_account", $data['parent_account']);
            $BTBuilder = $BTBuilder->whereIn("parent_account", $data['parent_account']);
        }

        if (isset($data['account'])) {
            $Builder = $Builder->whereIn("account", $data['account']);
            $SlotBuilder = $SlotBuilder->whereIn("account", $data['account']);
            $LotteryBuilder = $LotteryBuilder->whereIn("account", $data['account']);
            $BTBuilder = $BTBuilder->whereIn("account", $data['account']);
        }

        $account = $Builder->select("account")->distinct("account")->paginate($data['per_page']);

        $select_str = 'account,game_type,SUM(total_bet) as total_bet';
        $select_str .= ',SUM(valid_bet) as valid_bet,SUM(result) as result';
        if (isset($data['settle_list'])){
            if ($data['settle_list']!="member"){
                $select_str .= ',SUM(uplevel_occupy_money) as uplevel_occupy_money';
                $select_str .= ',SUM(occupy_money) as occupy_money,SUM(water_money) as water_money';
            }
        }
      

        $slot = $SlotBuilder->select(DB::raw($select_str))
            ->where("game_type", constant::SLOT_GAMETYPE)
            ->groupBy('account', 'game_type')->get()->keyBy("account");

        $lottery = $LotteryBuilder->select(DB::raw($select_str))
            ->where("game_type", constant::LOTTERY_GAMETYPE)
            ->groupBy('account', 'game_type')->get()->keyBy("account");


        $bt = $BTBuilder->select(DB::raw($select_str))
            ->where("game_type", constant::BT_GAMETYPE)
            ->groupBy('account', 'game_type')->get()->keyBy("account");

        return [
            "account" => $account,
            "slot" => $slot,
            "lottery" => $lottery,
            "bt" => $bt
        ];
    }


    public function companyList($data)
    {
        try {

            $Builder = new CompanyFs();
            $SlotBuilder = new CompanyFs();
            $LotteryBuilder = new CompanyFs();
            $BTGameBuilder = new CompanyFs();

            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];
            $data['settle_list']="company";
            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];
            $items = [];

            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }

    public function stationList($data)
    {

        try {
            $Builder = new StationFs();
            $SlotBuilder = new StationFs();
            $LotteryBuilder = new StationFs();
            $BTGameBuilder = new StationFs();

            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];
            $data['settle_list']="station";
            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];

            $items = [];
            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }

    public function companyAccountUnderStationList($company_id, $data)
    {
        try {
            $plucked  = Hierarchy::where('parent', $company_id)->where("level", Constant::STATION_LEVEL)->pluck('parent');
            $parent_ids = $plucked->all();
            $plucked1 = Hierarchy::whereIn("id", $parent_ids)->pluck("account");
            $Builder = new StationFs();
            $SlotBuilder = new StationFs();
            $LotteryBuilder = new StationFs();
            $BTGameBuilder = new StationFs();
            $parent_account = $plucked1->all();
            $data['parent_account'] = $parent_account;
            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];
            $data['settle_list']="station";
            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];

            $items = [];
            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }

    public function stationAccountUnderAgentList($station_id, $data)
    {

        try {
            $plucked  = Hierarchy::where('parent', $station_id)->where("level", Constant::AGENT_LEVEL)->pluck('parent');
            $parent_ids = $plucked->all();
            $plucked1 = Hierarchy::whereIn("id", $parent_ids)->pluck("account");
            $parent_account = $plucked1->all();

            $Builder = new AgentFs();
            $SlotBuilder = new AgentFs();
            $LotteryBuilder = new AgentFs();
            $BTGameBuilder = new AgentFs();
            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];

            $data['parent_account'] = $parent_account;
            $data['settle_list']="agent";
            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];

            $items = [];
            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }

    public function agentAccountUnderMembertList($agent_id, $data)
    {

        try {
            $plucked  = Member::where('agent_id', $agent_id)->pluck('account');
            $member_account = $plucked->all();
            $data['account'] = $member_account;

            $Builder = new MemberFs();
            $SlotBuilder = new MemberFs();
            $LotteryBuilder = new MemberFs();
            $BTGameBuilder = new MemberFs();

            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];
            $data['settle_list']="member";
            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];

            $items = [];
            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);

            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }

    public function memberList($data)
    {

        try {
            $Builder = new MemberFs();
            $SlotBuilder = new MemberFs();
            $LotteryBuilder = new MemberFs();
            $BTGameBuilder = new MemberFs();

            $Builders = [
                'builder' => $Builder,
                'slot' => $SlotBuilder,
                'lottery' => $LotteryBuilder,
                'bt' => $BTGameBuilder
            ];
            $data['settle_list']="member";

            $collectData = $this->BuilderFilter($Builders, $data);
            $account =  $collectData['account'];
            $slot =  $collectData['slot'];
            $lottery =  $collectData['lottery'];
            $bt =$collectData['bt'];

            $items = [];
            foreach ($account as $v) {
                $k = $v->account;
                if (isset($slot[$k])) {
                    $items[$k][] = $slot[$k];
                }
                if (isset($lottery[$k])) {
                    $items[$k][] = $lottery[$k];
                }

                if (isset($bt[$k])) {
                    $items[$k][] = $bt[$k];
                }
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return Paginator::format(
            $data["per_page"],
            $data["page"],
            $items,
            $account->currentPage(),
            $account->lastPage(),
            $account->total()
        );
    }
}
