<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\BetRecordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetRecordController extends Controller
{


    protected $service;

    public function __construct()
    {
        $this->service = new BetRecordService();
    }
    public function slotList(Request $request)
    {

        $res = $this->service->slotList($request->all());
        return Response::format(200, $res, "電子遊戲投注列表成功");
    }

    public function lotteryList(Request $request)
    {

        $res = $this->service->lotteryList($request->all());
        return Response::format(200, $res, "彩票遊戲投注列表成功");
    }


    public function slotCreate(Request $request)
    {

        $this->service->slotCreate($request->all());
        return Response::format(200, [], "歷史注單成功");
    }

    public function lotteryCreate(Request $request)
    {

        $data = $request->all();
        $account =(Auth::user())->account;
        $data['account']=$account;
        $this->service->checkPlayerBalance($data['account'],$data['bet_money']);
        $res = $this->service->lotteryCreate($data);
        return Response::format(200, $res, "歷史注單成功");
    }

    public function batchLotteryCreate(Request $request)
    {

        $data =$request->all();
        foreach($data as $record){
            $this->service->checkPlayerBalance($record['account'],$record['bet_money']);
        }
        $res = $this->service->batchLotteryCreate($data);
        return Response::format(200, $res, "歷史注單成功");
    }

    public function lotteryBetRecords(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $data['account'] = $user->account;
        $res = $this->service->lotteryBetRecords($data);
        return Response::format(200, $res, "列表成功");
    }


    public function lotteryBetRecordCancel(Request $request)
    {

        $this->service->lotteryBetRecordCancel($request->all());
        return Response::format(200, [], "取消成功");
    }


    public function slotBetRecordCancel(Request $request)
    {

        $this->service->slotBetRecordCancel($request->all());
        return Response::format(200, [], "取消成功");
    }
}
