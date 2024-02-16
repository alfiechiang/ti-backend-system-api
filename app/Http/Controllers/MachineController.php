<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Http\Requests\StoreMachineRequest;
use App\Http\Requests\UpdateMachineRequest;
use App\Http\Response;
use App\Http\Services\MachineService;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;

class MachineController extends Controller
{



    protected $service;
    protected $upladService;

    public function __construct()
    {
        $this->service = new MachineService();
        $this->upladService = new UploadService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = $request->all();
        $res = $this->service->list($data);
        return Response::format(200, $res, "電子機台列表成功");
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
        return Response::format(200, [], "新增電子機台成功");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMachineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMachineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function show(Machine $machine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function edit($machine_id)
    {
        //
        $data = $this->service->edit($machine_id);
        return Response::format(200, $data, "編輯電子機台成功");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMachineRequest  $request
     * @param  \App\Models\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $machine_id)
    {
        $this->service->update($machine_id, $request->all());
        return Response::format(200, [], "更新電子機台成功");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function destroy($machine_id)
    {
        //
        $this->service->destroy($machine_id);
        return Response::format(200, [], "刪除電子機台成功");
    }
}
