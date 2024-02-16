<?php

namespace App\Http\Logic;

use App\Models\LotteryOdd;
use App\Models\PickNumOdd;

class LotteryStakeLogic
{

    const UP = 1;
    const MIDDLE = 2;
    const DOWN = 3;
    const Chi = 4;
    const TIE = 5;
    const OU = 6;
    const BIG = 7;
    const SMALL = 8;
    const ODD = 9;
    const EVEN = 10;
    const BIG_ODD = 11;
    const SMALL_ODD = 12;
    const BIG_EVEN = 13;
    const SMALL_EVEN = 14;
    const INDEX_BIG = 15;
    const INDEX_SMALL = 16;
    const GOLD = 17;
    const TREE = 18;
    const WATER = 19;
    const FIRE = 20;
    const SOIL = 21;
    public $win_stake_ids = []; //該局注區中獎注區的ID
    public $options_stake=[]; //選號注區
    public $stakes=[]; //一般注區

    public $type; //彩種

    public $open_numbers;

    public $odds =0;

    function __construct($type,$numbers)
    {
        $this->type =$type;
        $this->open_numbers=$numbers;
        $this->setOptionStakeOdd($type);
        $this->setStakeOdd($type);
        $this->exec($numbers);
    }
    public  function exec($numbers)
    {
       $this->total_double_sided($numbers);
       $this->total_double_sided2($numbers);
       $this->five_element($numbers);
       $this->direct_plate($numbers);
       $this->odd_tie_even_plate($numbers);

    }

    public function caluResult($stake_id,$option_numbers,$bet_money){

        $OPTION_WIN=false;
        $WIN=false;
        $open_numbers=$this->open_numbers;
        $result=0;
        if(isset($this->stakes[$stake_id])){
            $stake =$this->stakes[$stake_id];
            $this->odds=$stake->odds;
        }
        
        foreach($this->win_stake_ids as $id){
            if ($id ==$stake_id){
                $WIN=true;
                $stake =$this->stakes[$stake_id];
                $odds=$stake->odds;
                $result =$bet_money*$odds;
            }
        } 

        if ($stake_id ==0 ){
            $odds=$this->findOneOptionStakeOdd($option_numbers,$open_numbers);
            $this->odds=$odds;
            if ($odds!=0){
                $OPTION_WIN=true;
                $result =$bet_money*$odds;
            }
        }

        if (!$OPTION_WIN && !$WIN){
            $result=-$bet_money;
        }


        return $result;

    }

    public function setStakeOdd($type){

        $pick = LotteryOdd::where("type", $type)->get();
        $group = $pick->groupBy("type");
        $keyed = $group->map(function ($item, int $key) {
            return $item->keyBy("stake_id");
        });
        $list = $keyed->all();
        $this->stakes = $list[$type];
    }

    public function setOptionStakeOdd($type)
    {

        $pick = PickNumOdd::where("type", $type)->get();
        $group = $pick->groupBy("zhung");
        $keyed = $group->map(function ($item, int $key) {
            return $item->keyBy("option");
        });
        $list = $keyed->all();
        $this->options_stake = $list;
    }
    //總數雙面
    public function total_double_sided($numbers)
    {

        $total = 0;
        foreach ($numbers as $key => $number) {
            $total += $number;
        }

        if ($total <= 810) {
            $this->win_stake_ids[] = LotteryStakeLogic::SMALL;
        } else {
            $this->win_stake_ids[] = LotteryStakeLogic::BIG;
        }
    }
    ////總數雙面串關
    public function total_double_sided2($numbers)
    {

        $total = 0;
        foreach ($numbers as $key => $number) {
            $total += $number;
        }

        if ($total > 810) {
            if ($total % 2 != 0) {
                $this->win_stake_ids[] = LotteryStakeLogic::BIG_ODD;
            } else {
                $this->win_stake_ids[] = LotteryStakeLogic::BIG_EVEN;
            }
        } else {
            if ($total % 2 == 0) {
                $this->win_stake_ids[] = LotteryStakeLogic::SMALL_EVEN;
            } else {
                $this->win_stake_ids[] = LotteryStakeLogic::SMALL_ODD;
            }
        }
    }

    //五元素
    public function five_element($numbers)
    {


        $total = 0;
        foreach ($numbers as $key => $number) {
            $total += $number;
        }

        if ($total >= 210 && $total <= 695) {
            $this->win_stake_ids[] = LotteryStakeLogic::GOLD;
        }

        if ($total >= 696 && $total <= 763) {
            $this->win_stake_ids[] = LotteryStakeLogic::TREE;
        }

        if ($total >= 764 && $total <= 855) {
            $this->win_stake_ids[] = LotteryStakeLogic::WATER;
        }

        if ($total >= 856 && $total <= 923) {
            $this->win_stake_ids[] = LotteryStakeLogic::FIRE;
        }

        if ($total >= 924 && $total <= 1410) {
            $this->win_stake_ids[] = LotteryStakeLogic::SOIL;
        }
    }

    //上中下盤
    public  function direct_plate($numbers)
    {

        $up_count = 0;
        $down_count = 0;
        foreach ($numbers as $key => $number) {
            if ($number <= 40) {
                $up_count++;
            } else {
                $down_count++;
            }
        }

        if ($up_count >= 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::UP;
        }

        if ($down_count >= 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::DOWN;
        }

        if ($up_count == 10 && $down_count == 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::MIDDLE;
        }
    }

    //奇和偶盤
    public function odd_tie_even_plate($numbers)
    {

        $odd_count = 0;
        $even_count = 0;

        foreach ($numbers as $key => $number) {
            if ($number % 2 == 0) {
                $even_count++;
            } else {
                $odd_count++;
            }
        }

        if ($odd_count > 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::Chi;
        }

        if ($even_count > 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::OU;
        }

        if ($odd_count == 10 && $even_count == 10) {
            $this->win_stake_ids[] = LotteryStakeLogic::TIE;
        }
    }

    //選號玩法
    public function findOneOptionStakeOdd($option_numbers,$open_numbers){
        $option_count =count($option_numbers);

        $zhung_count=0;

        foreach($option_numbers as $key1 =>$val1){

            foreach($open_numbers as $key2 =>$val2){
                if ($val1 ==$val2){
                    $zhung_count++;
                }
            }
        }

        $odds =0;
        if (isset($this->options_stake[$zhung_count][$option_count])){
            $item = $this->options_stake[$zhung_count][$option_count];
            $odds =$item->odds;
        }
        
        return $odds;
        
    }
}
