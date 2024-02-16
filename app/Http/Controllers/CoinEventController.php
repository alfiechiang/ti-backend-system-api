<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\CoinEventService;
use Illuminate\Http\Request;

class CoinEventController extends Controller
{
    protected $service;

    public function __construct()
    {

        $this->service = new CoinEventService();
    }

    public function member_edit($member_id)
    {
        $res = $this->service->member_edit($member_id);
        return Response::format(200, $res, "請求成功");
    }

    public function member_update(Request $request, $member_id)
    {
        $this->service->member_update($member_id, $request->all());
        return Response::format(200, [], "請求成功");
    }

    public function member_balance(Request $request)
    {

        $balance = $this->service->member_balance($request->all());
        return Response::format(200, ['balance' => $balance], "請求成功");
    }
}
