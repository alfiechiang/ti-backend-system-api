<?php

namespace App\Http\Controllers;

use App\Http\Constant;
use App\Http\Response;
use App\Http\Services\BalanceLogService;
use App\Http\Services\StationService;
use App\Models\Hierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    protected $service;
    protected $balanceLogService;
    public function __construct()
    {
        $this->service = new StationService();
        $this->balanceLogService = new BalanceLogService();
    }

    public function upLevelAccount(Request $request)
    {

        //判斷身分
        $user = Auth::user();
        $account = $user->account;

        if ($account == Constant::SUPERVISOR) {
            $data = Hierarchy::select('id as company_id', 'account as company_account')
                ->where('level', Constant::COMPANY_LEVEL)->get();
            return Response::format(200, $data, "上級帳號列表成功");
        }


        $hierarchy = Hierarchy::where("account", $account)->first();
        $level = $hierarchy->level;
        $data = [];
        switch ($level) {
            case Constant::COMPANY_LEVEL:
                $agent = $hierarchy;
                $company = [];
                $company['company_id'] = $agent->id;
                $company['company_account'] = $agent->account;
                $data[] = $company;
                break;
        }

        return Response::format(200, $data, "上級帳號列表成功");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = $request['per_page'];
        if (!empty($request['account'])) {
            $data = $this->service->searchList($per_page, $request['account']);
        } else {
            $data = $this->service->list($per_page);
        }
        //if (isset($data['in_account']) || isset($data['out_account'])) {


        return Response::format(200, $data, "站長列表成功");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //

        if (strcmp($request['password'], $request['confirm_password']) != 0) {
            return Response::format(200, [], "密碼與確認密碼不相同");
        }

        $this->service->create($request->all());
        return Response::format(200, [], "新增成功");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($station_id)
    {
        //
        $res = $this->service->edit($station_id);
        return Response::format(200, $res, "編輯成功");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $station_id)
    {

        if (strcmp($request['password'], $request['confirm_password']) != 0) {
            return Response::format(200, [], "密碼與確認密碼不相同");
        }

        $this->service->update($request->all(), $station_id);
        return Response::format(200, [], "更新成功");
    }

    public function occupyUpdate(Request $request, $station_id)
    {
        $this->service->occupyUpdate($request->all(), $station_id);
        return Response::format(200, [], "更新成功");
    }

    public function waterUpdate(Request $request, $station_id)
    {
        $this->service->waterUpdate($request->all(), $station_id);
        return Response::format(200, [], "更新成功");
    }

    public function balanceUpdate(Request $request, $station_id)
    {

        $this->service->balanceUpdate($request->all(), $station_id);
        return Response::format(200, [], "更新成功");
    }

    public function balanceLog(Request $request)
    {

        $data = $request->all();
        $data = $this->balanceLogService->searchList($data);


        return Response::format(200, $data, "額度列表成功");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
