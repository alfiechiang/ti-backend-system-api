<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemberRepository
{


    public function findOne($data){
        try {

            $member = Member::where("account",$data['account'])->first();
              
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $member;

    }


    public  function list($per_page)
    {
        # code...
        try {

            $data = Member::with("upLevel")
                ->with("upLevel.coin")
                ->with("upLevel.commercial")
                ->with("games")
                ->with("user.online")
                ->paginate($per_page);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $data;
    }

    public function searchList($per_page, $account)
    {
        $user = Auth::user();

        if ($user->account == Constant::SUPERVISOR) {

            $members = Member::with("upLevel")
                ->with("upLevel.coin")
                ->with("upLevel.commercial")
                ->with("games")
                ->where("account", $account)->paginate($per_page);
        } else {

            $hier = Hierarchy::where("account", $user->account)->first();
            $agent_ids = Hierarchy::where("level", Constant::AGENT_LEVEL)
                ->where('path', 'like', '%' . $hier->id . '%')->pluck('id');

            $members = Member::with("upLevel")
                ->with("upLevel.coin")
                ->with("upLevel.commercial")
                ->with("games")
                ->whereIn("agent_id", $agent_ids)
                ->where("account", $account)->paginate($per_page);
        }

        return $members;
    }

    public function companyUnderMemberList($per_page)
    {
        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $agent_ids = Hierarchy::where("level", Constant::AGENT_LEVEL)
            ->where('path', 'like', '%' . $hier->id . '%')->pluck('id');

        $members = Member::with("upLevel")
            ->with("upLevel.coin")
            ->with("upLevel.commercial")
            ->with("games")
            ->whereIn("agent_id", $agent_ids)->paginate($per_page);

        return $members;
    }

    public function stationUnderMemberList($per_page)
    {

        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $agent_ids = Hierarchy::where("level", Constant::AGENT_LEVEL)
            ->where('path', 'like', '%' . $hier->id . '%')->pluck('id');
        $members = Member::with("upLevel")
            ->with("upLevel.coin")
            ->with("upLevel.commercial")
            ->with("games")
            ->whereIn("agent_id", $agent_ids)->paginate($per_page);

        return $members;
    }

    public function agentUnderMemberList($per_page)
    {
        $user = Auth::user();
        $hier = Hierarchy::where("account", $user->account)->first();
        $agent_ids = Hierarchy::where("level", Constant::AGENT_LEVEL)
            ->where('path', 'like', '%' . $hier->id . '%')->pluck('id');
        $members = Member::with("upLevel")
            ->with("upLevel.coin")
            ->with("upLevel.commercial")
            ->with("games")
            ->whereIn("agent_id", $agent_ids)->paginate($per_page);

        return $members;
    }

    public  function create($data)
    {

        # code...
        try {
            $member = Member::create([
                "agent_id" => $data['agent_id'],
                "account" => $data['account'],
                "name" => $data['name'],
                "phone" => $data['phone'],
                "balance" => $data['balance'],
                "freeze_status" => $data['freeze_status'],
                "has_test_account" => $data['has_test_account'],
                "balance_desc" => $data['balance_desc'],
                "desc" => $data['desc']
            ]);
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $member->id;
    }

    public  function edit($member_id)
    {
        $data = Member::with('games')->with("user.online")->find($member_id);

        $data['online'] =  $data->online;
        return $data;
    }

    public  function balanceEdit($member_id)
    {
        $data = Member::select("account", "name", "balance", "desc", "agent_id")->find($member_id);
        return $data;
    }

    public  function update($data, $member_id)
    {

        try {

            $model = Member::find($member_id);
            $model->name = $data['name'];
            $model->phone = $data['phone'];
            $model->freeze_status = $data['freeze_status'];
            $model->has_test_account = $data['has_test_account'];
            $model->desc = $data['desc'];
            $model->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public  function passpordUpdate($member_id, $password)
    {


        $member = Member::find($member_id);
        $user =  User::where("account", $member->account)->first();
        $user->password = $password;
        $user->save();
    }



    public function balanceUpdate($data, $member_id)
    {
        try {
            $model = Member::find($member_id);
            $model->balance = $model->balance + $data['balance'];
            $model->balance_desc = $data['desc'];

            if(isset($data['balance_limit'])){
                $model->balance_limit=$data['balance_limit'];
            }
            $model->save();

            $parent = Hierarchy::find($model->agent_id);
            $parent->balance = $parent->balance - $data['balance'];
            $parent->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }
}
