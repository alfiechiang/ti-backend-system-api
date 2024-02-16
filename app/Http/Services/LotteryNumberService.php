<?php

namespace App\Http\Services;
use App\Http\Repositorys\LotteryNumberRepository;

class LotteryNumberService
{

    protected $repository;


    public function __construct()
    {
        $this->repository = new LotteryNumberRepository();
    }

    public function typeLotteryNumber($data)
    {

        $res = $this->repository->typeLotteryNumber($data);
        return $res;
    }
}
