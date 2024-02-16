<?php

namespace App\Http\Services;

use App\Http\Repositorys\GameRepository;
use App\Http\Repositorys\LotteryOddRepository;
use App\Http\Repositorys\MemberRepository;

class LotteryOddService
{

    protected $repository;


    public function __construct()
    {
        $this->repository = new LotteryOddRepository();
    }

    public function typeList($data)
    {

        $data = $this->repository->typeList($data['type']);
        return $data;
    }

    public function optionList($data)
    {

        $data = $this->repository->optionList($data['type']);
        return $data;
    }
}
