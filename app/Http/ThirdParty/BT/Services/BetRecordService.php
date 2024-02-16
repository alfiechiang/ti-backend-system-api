<?php

namespace App\Http\ThirdParty\BT\Services;

use App\Http\ThirdParty\BT\Repositorys\BetRecordRepository;

class BetRecordService
{



    protected $repository;
    public function __construct()
    {
        $this->repository = new BetRecordRepository();
    }

    public function list($data){

       $data= $this->repository->list($data);
       return $data;

    }




}
