<?php

namespace App\Http\Repositorys;

use App\Http\Constant;
use App\Models\Hierarchy;
use App\Models\Member;
use App\Models\PlayerBalanceLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserRepository
{


    public  function create($data)
    {

        $role_ch_name = "";
        $roleID = 0;
        if (isset($data['level'])) {
            switch ($data['level']) {
                case Constant::COMPANY_LEVEL:
                    $role_ch_name = "公司";
                    break;
                case Constant::STATION_LEVEL:
                    $role_ch_name = "站長";
                    break;
                case Constant::AGENT_LEVEL:
                    $role_ch_name = "代理";
                    break;
            }
            $role = Role::where("name", $role_ch_name)->first();
            $roleID = $role->id;
        }

        $data['role_id'] = $roleID;
        $data['password'] = bcrypt($data['password']);
        # code...
        try {
            User::create($data);
        } catch (Exception $e) {
            Log::error($e);

            dd($e);
        }
    }


    public  function edit($account)
    {
        # code...
        try {
            $data =  User::where('account', $account)->first();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public  function update($hierarchy_id, $data)
    {
        # code...
        try {
            $hier = Hierarchy::find($hierarchy_id);
            $user_collection =  User::where('account', $hier->account)->first();
            if (!empty($data['password'])) {
                $user_collection['password'] = bcrypt($data['password']);
            }
            $user_collection->save();
        } catch (Exception $e) {
            Log::error($e);

            dd($e);
        }
    }

    public function changePassword($user_id, $password)
    {
        try {
            $user = User::find($user_id);
            $user->password = bcrypt($password);
            $user->save();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public function getLevelID($account)
    {

        try {
            $hierarchy =   Hierarchy::where("account", $account)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }

        return $hierarchy->id;
    }


    public function playerInfo($account)
    {

        try {
            $member =   Member::where("account", $account)->first();
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
        return $member;
    }

    public function memberBalanceChange($account, $balance, $category)
    {
        try {

            $money = DB::transaction(function () use ($account, $balance, $category) {

                $member =   Member::where("account", $account)->first();
                $balance_before = $member->balance;
                $member->balance = $member->balance + $balance;
                $balance_after = $member->balance;
                $member->save();

                PlayerBalanceLog::create([
                    'account' => $account,
                    'change_time' => date('Y-m-d H:i:s'),
                    'game_category' => $category,
                    'balance_before' => $balance_before,
                    'expense' => $balance_after - $balance_before,
                    'balance_after' => $balance_after

                ]);

                return $member->balance;
            });

            return $money;
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }


    public function palyerBalanceLog($account, $data)
    {
        $Builder = new PlayerBalanceLog();
        if (!empty($account)) {
            $Builder = $Builder->where("account", $account);
        }

        if (!empty($data['starttime']) && !empty($data['endtime'])) {

            $Builder = $Builder->where("change_time", ">=", $data['starttime'])
                ->where("change_time", "<=", $data['endtime']);
        }

        $data = $Builder->orderBy('created_at', 'desc')->paginate($data['per_page']);

        $refacter = $data->map(function ($item, $key) {
            $item->game_category=  $item->game_category();
            return $item ;
        });
        $data=$refacter->all();

        return $data;
    }
}
