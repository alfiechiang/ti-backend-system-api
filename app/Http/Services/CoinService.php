<?php

namespace App\Http\Services;

use App\Http\Repositorys\CoinRepository;

class CoinService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new CoinRepository();
    }

    public  function create($data)
    {
        # code...
        $this->repository->create($data);
    }

    public  function list()
    {
        # code...
        $data = $this->repository->list();

        return $data;
    }

    public  function searchList($coin_name)
    {
        # code...
        $data = $this->repository->searchList($coin_name);

        return $data;
    }

    public  function accountCoinList($username)
    {
        # code...
        $data = $this->repository->accountCoinList($username);

        return $data;
    }


    public function destroy($coinId)
    {
        $this->repository->destroy($coinId);
    }
}
