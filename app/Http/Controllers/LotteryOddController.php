<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\LotteryOddService;
use Illuminate\Http\Request;

class LotteryOddController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new LotteryOddService();
    }
    /**     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function typeList(Request $request)
    {
        $res = $this->service->typeList($request->all());

        return Response::format(200, $res, "列表成功");
    }


    public function optionList(Request $request)
    {
        $res = $this->service->optionList($request->all());

        return Response::format(200, $res, "列表成功");
    }

   

}
