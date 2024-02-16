<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\SettlementService;
use Illuminate\Http\Request;

class SettlementController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new SettlementService();
    }
    //
    public function companyList(Request $request)
    {

        $data = $this->service->companyList($request->all());
        // dd($data);
        return Response::format(200, $data, "公司結算報表成功");
    }

    public function stationList(Request $request)
    {

        $data = $this->service->stationList($request->all());
        // dd($data);
        return Response::format(200, $data, "站長結算報表成功");
    }

    public function agentList(Request $request)
    {

        $data = $this->service->agentList($request->all());
        // dd($data);
        return Response::format(200, $data, "代理結算報表成功");
    }

    public function memberList(Request $request)
    {

        $data = $this->service->memberList($request->all());
        // dd($data);
        return Response::format(200, $data, "會員結算報表成功");
    }

    //該彩種當局注單輸贏結果
    public  function calcLotteryResult(Request $request)
    {
       $this->service->calcLotteryResult($request->all());
       return Response::format(200, [], "請求成功");

    }
}
