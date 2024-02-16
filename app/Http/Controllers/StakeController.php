<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\StakeService;
use Illuminate\Http\Request;

class StakeController extends Controller
{

    protected $service;
    public function __construct()
    {
        $this->service = new StakeService();
    }
    public function index(Request $request)
    {
        $res = $this->service->list($request->all());

        return Response::format(200, $res, "列表成功");
    }

    public function batchUpdate(Request $request)
    {
        $this->service->batchUpdate($request->all());

        return Response::format(200, [], "更新成功");
    }
}
