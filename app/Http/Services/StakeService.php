<?php

namespace App\Http\Services;

use App\Http\Repositorys\StakeRepository;

class StakeService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new StakeRepository();
    }

    public  function list($data)
    {

        $res = $this->repository->list($data);
        return $res;
    }

    public  function batchUpdate($data)
    {

        $this->repository->batchUpdate($data);
    }


   
}
