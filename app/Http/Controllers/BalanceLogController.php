<?php

namespace App\Http\Controllers;

use App\Models\BalanceLog;
use App\Http\Requests\StoreBalanceLogRequest;
use App\Http\Requests\UpdateBalanceLogRequest;

class BalanceLogController extends Controller
{


   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


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
     * @param  \App\Http\Requests\StoreBalanceLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBalanceLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BalanceLog  $balanceLog
     * @return \Illuminate\Http\Response
     */
    public function show(BalanceLog $balanceLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BalanceLog  $balanceLog
     * @return \Illuminate\Http\Response
     */
    public function edit(BalanceLog $balanceLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBalanceLogRequest  $request
     * @param  \App\Models\BalanceLog  $balanceLog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBalanceLogRequest $request, BalanceLog $balanceLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BalanceLog  $balanceLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(BalanceLog $balanceLog)
    {
        //
    }
}
