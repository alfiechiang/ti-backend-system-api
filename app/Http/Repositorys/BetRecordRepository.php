<?php

namespace App\Http\Repositorys;

use App\Exceptions\ResponseException;
use App\Http\Constant;
use App\Models\LotteryOdd;
use App\Models\LotteryRecord;
use App\Models\Machine;
use App\Models\Member;
use App\Models\SlotReocrd;
use App\Models\Stake;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class BetRecordRepository
{

    protected $playerBalanceLogRepository;


    public function slotList($data)
    {

        $Builder = SlotReocrd::where('account', $data['account']);
        if (!empty($data['starttime']) && !empty($data['endtime'])) {

            $Builder = $Builder->where("open_score_time", ">=", $data['starttime'])
                ->where("open_score_time", "<=", $data['endtime']);
        }
        $data = $Builder->where("caculate", true)->where("type","SLOT") //實體機代號ＳＬＯＴ
        ->orderBy('created_at', 'desc')->paginate($data['per_page']);

        return $data;
    }

    public function lotteryList($data)
    {

        $Builder = LotteryRecord::where('account', $data['account']);
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $Builder = $Builder->where("bet_time", ">=", $data['starttime'])
                ->where("bet_time", "<=", $data['endtime']);
        }

        $data = $Builder->orderBy('bet_time', 'desc')->paginate($data['per_page']);

        return $data;
    }


    public function checkmemberBalance($account,$bet_money){

        $member =Member::where("account",$account)->first();
        if ($member->balance <$bet_money){
            throw new ResponseException("餘額不足");
        }

        if ($member->balance >$member->balance_limit){
            throw new ResponseException("餘額超過遊戲限額");
        }

    }


    public function slotCreate($data)
    {
        try {
            $member = Member::where("account", $data['account'])->first();
            if ($member->has_test_account) {
                $data['caculate'] = false;
            }

            if (!empty($data['machine_model'])) {
                $machine = Machine::where("machine_model", $data['machine_model'])->first();
                $data['machine_name'] = $machine->machine_name;
            }
            SlotReocrd::create($data);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function lotteryCreate($data)
    {
        try {
            $total = 0;
            $member = Member::where("account", $data['account'])->first();
            $data['status'] = 0;
            if ($member->has_test_account) {
                $data['caculate'] = false; //不列入計算
            }
            $total += $data['bet_money'];
            if ($data['stake_id']!=0){  //非選號情形
                $lotteryOdd=LotteryOdd::where('type',$data['type'])->where('stake_id',$data['stake_id'])->first();
                $data['odds']=$lotteryOdd->odds;
            }
           
            LotteryRecord::create($data);
            $account = $data["account"];
            $member = Member::where("account", $account)->first();
            $balance_before = $member->balance;
            $member->balance = $member->balance - $total;
            $balance_after =$member->balance;
            $category =Constant::LOTTERY;
            $member->save();
            $this->playerBalanceLogRepository=new PlayerBalanceLogRepository();
            ## 帳變紀錄
            $this->playerBalanceLogRepository->create($account,$category,$balance_before,$balance_after);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function batchLotteryCreate($datas)
    {
       foreach($datas as $data){
            $this->lotteryCreate($data);
       }
    }

    public function lotteryBetRecords($data)
    {

        $Builder = LotteryRecord::where('account', $data['account']);

        if (!empty($data['type'])) {
            $Builder = $Builder->where("type", $data['type']);
        }

        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            $Builder = $Builder->where("bet_time", ">=", $data['starttime'])
                ->where("bet_time", "<=", $data['endtime']);
        }

        if (isset($data['status'])) {
            $Builder = $Builder->where("status", $data['status']);
        }

        $res = $Builder->get();
        return $res;
    }

    public function roundLotteryTypeBetRecords($period, $type,$status=0)
    {
        try {
            $records = LotteryRecord::where("type", $type)->where("status",$status)->where("period", $period)->get();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $records;
    }

    public function DeleteTypePeriodBetRecords($period, $type)
    {

        try {
            LotteryRecord::where("period", $period)->where("type", $type)->delete();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function InsertTypePeriodResultBetRecords($insertData)
    {

        try {
            LotteryRecord::insert(collect($insertData)->toArray());
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function lotteryBetRecordCancel($data)
    {

        try {

            $record = LotteryRecord::where("type", $data['type'])
                ->where("id", $data['id'])
                ->where("account", $data['account'])->where("period", $data['period'])
                ->first();

            $record->status = Constant::LOTTERY_BET_RECORD_CANCEL;
            $record->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function slotBetRecordCancel($data)
    {
        try {

            $record = SlotReocrd::where("type", $data['type'])
                ->where("id", $data['id'])
                ->where("account", $data['account'])
                ->first();
            $record->status = Constant::LOTTERY_BET_RECORD_CANCEL;
            $record->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
