<?php

namespace App\Http\Controllers;

use App\Http\Constant;
use App\Http\Response;
use App\Http\Services\BalanceLogService;
use App\Http\Services\MemberService;
use App\Http\Services\UploadService;
use App\Models\Hierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{


    protected $service;
    protected $balanceLogService;

    public function __construct()
    {
        $this->service = new MemberService();
        $this->balanceLogService = new BalanceLogService();
    }

    public function upLevelAccount(Request $request)
    {

        $data = $this->service->upLevelAccount();
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

        return Response::format(200, $data, "列表成功");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
    public function edit($member_id)
    {
        $data = $this->service->edit($member_id);
        return Response::format(200, $data, "編輯成功");
    }


    public function balanceEdit($member_id)
    {
        $data = $this->service->balanceEdit($member_id);
        return Response::format(200, $data, "編輯成功");
    }

    public function balanceUpdate(Request $request, $member_id)
    {
        $this->service->balanceUpdate($request->all(), $member_id);
        return Response::format(200, [], "更新成功");
    }


    //balanceEdit

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $member_id)
    {
        //
        $this->service->update($request->all(), $member_id);
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
