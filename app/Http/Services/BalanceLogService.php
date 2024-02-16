<?php

namespace App\Http\Services;

use App\Http\Constant;
use App\Http\Repositorys\BalanceLogRepository;

class BalanceLogService
{


    protected $repository;
    public function __construct()
    {
        $this->repository = new BalanceLogRepository();
    }


    public  function searchList($data)
    {

        $data = $this->repository->searchList($data);
        return $data;
    }
}
