<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Http\Services\BalanceLogService;
use App\Http\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

    protected $service;

    protected $balanceLogService;

    public function __construct()
    {

        $this->service = new CompanyService();
        $this->balanceLogService = new BalanceLogService();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = $request['per_page'];
        $data = $request->all();
        unset($data['per_page']);
        if (count($data) > 0) {
            $data = $this->service->searchList($per_page, $data);
        } else {
            $data = $this->service->list($per_page);
        }

        return Response::format(200, $data, "列表成功");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $this->service->create($request->all());
        return Response::format(200, [], "新增成功");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id)
    {
        //
        $data = $this->service->edit($company_id);
        return Response::format(200, $data, "編輯成功");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id)
    {

        $this->service->update($request->all(), $company_id);
        return Response::format(200, [], "更新成功");
    }

    public function occupyUpdate(Request $request, $company_id)
    {
        $this->service->occupyUpdate($request->all(), $company_id);
        return Response::format(200, [], "更新成功");
    }

    public function waterUpdate(Request $request, $company_id)
    {
        $this->service->waterUpdate($request->all(), $company_id);
        return Response::format(200, [], "更新成功");
    }

    public function balanceUpdate(Request $request, $company_id)
    {
        $this->service->balanceUpdate($request->all(), $company_id);
        return Response::format(200, [], "更新成功");
    }

    public function balanceLog(Request $request)
    {

        $data = $request->all();
        $data = $this->balanceLogService->searchList($data);

        return Response::format(200, $data, "額度列表成功");
    }
}
