<?php

namespace App\Http\Services;

use App\Http\Repositorys\BetRecordRepository;
use App\Http\Repositorys\MemberRepository;

class BetRecordService
{
    protected $repository;
    protected $memberRepository;

    



    public function __construct()
    {
        $this->repository = new BetRecordRepository();


        $this->memberRepository =new MemberRepository();

    }


    public function slotList($data)
    {

        $res = $this->repository->slotList($data);
        return $res;
    }

    public function lotteryList($data)
    {
        $res = $this->repository->lotteryList($data);
        return $res;
    }

    public function slotCreate($data)
    {
        $this->repository->slotCreate($data);
    }

    public function checkPlayerBalance($account,$bet_money){
        $this->repository->checkmemberBalance($account,$bet_money);

    }

    public function lotteryCreate($data)
    {
       $this->repository->lotteryCreate($data);
       $member= $this->memberRepository->findOne(["account"=>$data["account"]]);
       $res=[];
       $res["balance"]=$member->balance;

       return $res;

    }

    public function batchLotteryCreate($data)
    {
        $this->repository->batchLotteryCreate($data);
        $account =$data[0]["account"];
        $member= $this->memberRepository->findOne(["account"=>$account]);
        $res=[];
        $res["balance"]=$member->balance;

        return $res;
    }

    public function lotteryBetRecords($data){
        $res =  $this->repository->lotteryBetRecords($data);
        return $res;
    }
    
    public function lotteryBetRecordCancel($data){
        $this->repository->lotteryBetRecordCancel($data);
    }

    public function slotBetRecordCancel($data){

        $this->repository->slotBetRecordCancel($data);


    }


}
