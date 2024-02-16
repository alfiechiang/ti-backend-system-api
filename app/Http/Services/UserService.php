<?php

namespace App\Http\Services;

use App\Http\Repositorys\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function changePassword($user_id, $password)
    {
        $this->repository->changePassword($user_id, $password);
    }


    public function passwordIndex()
    {
        $user = Auth::user();
        $user_info = [];
        $user_info['password'] = "";
        return $user_info;
    }


    public function getLevelID($account)
    {

        $levelID = $this->repository->getLevelID($account);

        return $levelID;
    }

    public function playerInfo()
    {
        $user = Auth::user();
        $member = $this->repository->playerInfo($user->account);

        return [
            "name" => $member->name,
            "phone" => $member->phone,
            "balance"=>$member->balance,
            "balance_limit"=>$member->balance_limit
        ];
    }


    public function memberBalanceChange($account, $balance, $category)
    {
        $balance = $this->repository->memberBalanceChange($account, $balance, $category);
        return [
            "balance" => $balance
        ];
    }


    public function palyerBalanceLog($account, $data)
    {

        $res = $this->repository->palyerBalanceLog($account, $data);
        return $res;
    }
}
