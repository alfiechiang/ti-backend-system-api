<?php

namespace App\Http\Controllers;

use App\Models\MemberGameStatus;
use App\Http\Requests\StoreMemberGameStatusRequest;
use App\Http\Requests\UpdateMemberGameStatusRequest;

class MemberGameStatusController extends Controller
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
     * @param  \App\Http\Requests\StoreMemberGameStatusRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemberGameStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemberGameStatus  $memberGameStatus
     * @return \Illuminate\Http\Response
     */
    public function show(MemberGameStatus $memberGameStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemberGameStatus  $memberGameStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberGameStatus $memberGameStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMemberGameStatusRequest  $request
     * @param  \App\Models\MemberGameStatus  $memberGameStatus
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberGameStatusRequest $request, MemberGameStatus $memberGameStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemberGameStatus  $memberGameStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberGameStatus $memberGameStatus)
    {
        //
    }
}
