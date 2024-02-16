<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\LotteryNumberService;
use App\Http\Services\LotteryService;
use Illuminate\Http\Request;

class LotteryNumberController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new LotteryNumberService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function typeLotteryNumber(Request $request)
    {       

        $res = $this->service->typeLotteryNumber($request->all());
        return Response::format(200, $res, "列表成功");
    }


}
