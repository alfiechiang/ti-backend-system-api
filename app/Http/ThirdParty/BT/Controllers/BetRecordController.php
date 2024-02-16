<?php

namespace App\Http\ThirdParty\BT\Controllers;

use App\Http\Controllers\Controller;
use App\Http\ThirdParty\BT\Services\BetRecordService;
use Illuminate\Http\Request;
use App\Http\Response;


class BetRecordController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new BetRecordService();
    }

    public function list(Request $request){
        $res = $this->service->list($request->all());
        return Response::format(200, $res, "BT電子投注明細");
    }





   
}
