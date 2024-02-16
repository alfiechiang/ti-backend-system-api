<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\LotteryService;
use Illuminate\Http\Request;

class LotteryController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new LotteryService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(Request $request)
    {
        $res = $this->service->pageList($request->all());

        return Response::format(200, $res, "列表成功");
    }


    public function findOne(Request $request,$type)
    {
        $res = $this->service->findOne($type);

        return Response::format(200, $res, "編輯成功");
    }

    public function list(Request $request)
    {
        $res = $this->service->list();
        return Response::format(200, $res, "列表成功");
    }

    public function edit(Request $request,$type)
    {
        $res = $this->service->edit($type);

        return Response::format(200, $res, "編輯成功");
    }

   

    public function update(Request $request,$type)
    {
        $this->service->update($request->all(),$type);

        return Response::format(200,[], "更新成功");
    }
    public function periodList(Request $request)
    {
        $res = $this->service->periodList($request->all());

        return Response::format(200, $res, "列表成功");
    }

    public function periodBatchUpdate(Request $request){
        $this->service->periodBatchUpdate($request->all());
        return Response::format(200,[], "更新成功");
    }


    public function caculate(Request $request){

        $this->service->caculate($request->all());
        return Response::format(200,[], "請求成功");

    }

}
