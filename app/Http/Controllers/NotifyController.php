<?php

namespace App\Http\Controllers;

use App\Models\Notify;
use App\Http\Requests\StoreNotifyRequest;
use App\Http\Requests\UpdateNotifyRequest;
use App\Http\Response;
use App\Http\Services\NotifyService;
use Illuminate\Http\Request;

class NotifyController extends Controller
{

    protected $service;
    public function __construct()
    {
        $this->service = new NotifyService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->service->list();

        return Response::format(200, $data, "通知列表成功");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNotifyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotifyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notify  $notify
     * @return \Illuminate\Http\Response
     */
    public function show(Notify $notify)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notify  $notify
     * @return \Illuminate\Http\Response
     */
    public function edit(Notify $notify)
    {
        //
    }


    public function update(Request $request)
    {
        //
        $this->service->update($request->all());
        return Response::format(200, [], "公告更新成功");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notify  $notify
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notify $notify)
    {
        //
    }
}
