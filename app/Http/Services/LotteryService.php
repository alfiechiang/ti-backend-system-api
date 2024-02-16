<?php

namespace App\Http\Services;

use App\Http\Repositorys\LotteryRepository;
use App\Http\Repositorys\MachineRepository;
use Illuminate\Support\Facades\Auth;

class LotteryService
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new LotteryRepository();
    }

    public  function pageList($data)
    {

        $res = $this->repository->pageList($data);
        return $res;
    }

    public  function findOne($type)
    {

        
        $res = $this->repository->findOne($type);
        return $res;
    }


    public  function list()
    {
        $res = $this->repository->list();
        return $res;
    }


    public  function edit($type)
    {

        
        $res = $this->repository->edit($type);
        return $res;
    }


    public  function update($data,$type)
    {

        $res = $this->repository->update($data,$type);
    }

    public function periodList($data){

        $res = $this->repository->periodList($data);
        return $res;
    }

    public function periodBatchUpdate($data){
        $this->repository->periodBatchUpdate($data);
    }

    public function caculate($data){

        return "";
    }

   
}
