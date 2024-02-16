<?php

namespace App\Http\Services;

use App\Http\Repositorys\CoinEventRepository;

class CoinEventService
{

    protected $repository;



    public function __construct()
    {
        $this->repository = new CoinEventRepository();
    }

    public function member_edit($member_id)
    {
        $res = $this->repository->member_edit($member_id);
        return $res;
    }

    public function member_update($member_id, $data)
    {
        $this->repository->member_update($member_id, $data);
    }

    public function member_balance($data)
    {

      $balance=  $this->repository->member_balance($data['account'], $data['coin_name'], $data['balance']);
      return $balance;
    }
}
