<?php

namespace App\Http\Services;

use App\Exceptions\ResponseException;
use App\Http\Constant;
use App\Http\Logic\LotteryStakeLogic;
use App\Http\Repositorys\AgentFsRepository;
use App\Http\Repositorys\BetRecordRepository;
use App\Http\Repositorys\CompanyFsRepository;
use App\Http\Repositorys\LotteryNumberRepository;
use App\Http\Repositorys\MemberFsRepository;
use App\Http\Repositorys\PlayerBalanceLogRepository;
use App\Http\Repositorys\SettlementRepository;
use App\Http\Repositorys\StationFsRepository;
use App\Models\AgentFs;
use App\Models\CompanyFs;
use App\Models\Game;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\MemberFs;
use App\Models\SlotReocrd;
use App\Models\StationFs;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettlementService
{

    protected $repository;
    protected $memberFsRepository;
    protected $agentFsRepository;
    protected $stationFsRepository;

    protected $companyFsRepository;

    protected $betRecordRepository;

    protected $lotteryNumberRepository;


    protected $playerBalanceLogRepository;



    public function __construct()
    {
        $this->repository = new SettlementRepository();
        $this->memberFsRepository = new MemberFsRepository();
        $this->agentFsRepository = new AgentFsRepository();
        $this->stationFsRepository = new StationFsRepository();
        $this->companyFsRepository = new CompanyFsRepository();
        $this->betRecordRepository = new BetRecordRepository();
        $this->lotteryNumberRepository = new LotteryNumberRepository();
        $this->playerBalanceLogRepository = new PlayerBalanceLogRepository();

    }

    public function companyList($data)
    {

        $data = $this->repository->companyList($data);
        return $data;
    }

    public function stationList($data)
    {

        if (isset($data['uplevel_id'])) {
            $company_id = $data['uplevel_id'];
            unset($data['uplevel_id']);
            $res = $this->repository->companyAccountUnderStationList($company_id, $data);
        } else {
            $res = $this->repository->stationList($data);
        }
        return $res;
    }

    public function agentList($data)
    {
        if (isset($data['uplevel_id'])) {
            $station_id = $data['uplevel_id'];
            unset($data['uplevel_id']);
            $res = $this->repository->stationAccountUnderAgentList($station_id, $data);
        } else {
            $res = $this->repository->stationList($data);
        }
        return $res;
    }

    public function memberList($data)
    {

        if (isset($data['uplevel_id'])) {
            $agent_id = $data['uplevel_id'];
            unset($data['uplevel_id']);
            $res = $this->repository->agentAccountUnderMembertList($agent_id, $data);
        } else {

            $res = $this->repository->memberList($data);
        }
        return $res;
    }

    public function caculate($date)
    {
        $this->slotCaculate($date); //slot實體機計算
        $this->lotteryCaculate($date);//彩票計算
        $this->btCaculate($date); //ＢＴ電子計算
    }

    private function slotCaculate($date)
    {

        try {

            DB::transaction(function () use ($date) {
                $game = Game::where("name", "slot")->first();
                $game_type = $game->game_type;
                MemberFs::where("date", $date)->where("game_type", $game_type)->delete();
                AgentFs::where("date", $date)->where("game_type", $game_type)->delete();
                StationFs::where("date", $date)->where("game_type", $game_type)->delete();
                CompanyFs::where("date", $date)->where("game_type", $game_type)->delete();
                //會員總帳
                $this->memberFsRepository->slotCaculate($date);
                //代理總帳
                $this->agentFsRepository->slotCaculate($date);
                //站長總帳
                $this->stationFsRepository->slotCaculate($date);
                //公司總帳
                $this->companyFsRepository->slotCaculate($date);
            });
        } catch (Exception $e) {

            Log::error($e);
            dd($e);
        }
    }

    private function btCaculate($date)
    {

        try {

            DB::transaction(function () use ($date) {
                $game = Game::where("name", "bt")->first();
                $game_type = $game->game_type;
                MemberFs::where("date", $date)->where("game_type", $game_type)->delete();
                AgentFs::where("date", $date)->where("game_type", $game_type)->delete();
                StationFs::where("date", $date)->where("game_type", $game_type)->delete();
                CompanyFs::where("date", $date)->where("game_type", $game_type)->delete();
                //會員總帳
                $this->memberFsRepository->btCaculate($date);
                //代理總帳
                $this->agentFsRepository->btCaculate($date);
                //站長總帳
                $this->stationFsRepository->btCaculate($date);
                //公司總帳
                $this->companyFsRepository->btCaculate($date);
            });
        } catch (Exception $e) {

            Log::error($e);
            dd($e);
        }
    }

    private function lotteryCaculate($date)
    {

        try {
            DB::transaction(function () use ($date) {
                $game = Game::where("name", "lottery")->first();
                $game_type = $game->game_type;
                MemberFs::where("date", $date)->where("game_type", $game_type)->delete();
                AgentFs::where("date", $date)->where("game_type", $game_type)->delete();
                StationFs::where("date", $date)->where("game_type", $game_type)->delete();
                CompanyFs::where("date", $date)->where("game_type", $game_type)->delete();
                //會員總帳
                $this->memberFsRepository->lotteryCaculate($date);
                //代理總帳
                $this->agentFsRepository->lotteryCaculate($date);
                //站長總帳
                $this->stationFsRepository->lotteryCaculate($date);
                //公司總帳
                $this->companyFsRepository->lotteryCaculate($date);
            });
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function calcLotteryResult($data)
    {


        $records = $this->betRecordRepository->roundLotteryTypeBetRecords($data['period'], $data['type']);
        if ($records->isEmpty()){
            return ;
        }
        $type_period = $this->lotteryNumberRepository->findOne($data);
        if (is_null($type_period)){
            throw new ResponseException("未有該彩票期數");
        }
        $number_str = $type_period->numbers;
        $numbers = array_map('intval', explode(',', $number_str));
        $logic = new LotteryStakeLogic($data['type'], $numbers);
        $cacuResult = $records->map(function ($item, int $key) use ($logic) {
            $stake_id = $item->stake_id;
            $option_number = "";
            $bet_money = $item->bet_money;
            if ($stake_id == 0) {
                $option_number = array_map('intval', explode(',', $item->stake_name));
            }
            $item->result = $logic->caluResult($stake_id, $option_number, $bet_money); //計算每個注單輸贏結果
            $item->status = Constant::LOTTERY_BET_RECORD_SETTLE;
            $item->odds =$logic->odds;
            return $item;
        });
        $list = $cacuResult->all();

        $this->lotteryPlayerBalanceLog($list); //帳變紀錄

        DB::transaction(function () use ($data, $list) {
            if (empty($list)) {
                return;
            }
            $this->betRecordRepository->DeleteTypePeriodBetRecords($data['period'], $data['type']);
            $this->betRecordRepository->InsertTypePeriodResultBetRecords($list);
        });
    }

    public function lotteryPlayerBalanceLog($list){
        foreach ($list as $record) {
            $account = $record['account'];
            $result = $record['result'];
            if ($result <0){
                continue;
            }
            $member = Member::where("account", $account)->first();
            $balance_before=$member->balance;
            $member->balance = $member->balance + $result;
            $balance_after = $member->balance;
            $member->save();
            $category=Constant::LOTTERY;
            $this->playerBalanceLogRepository->create($account,$category,$balance_before,$balance_after);
        }

    }
}
