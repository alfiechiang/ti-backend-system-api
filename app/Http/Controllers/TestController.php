<?php

namespace App\Http\Controllers;

use App\Http\Logic\LotteryStakeLogic;
use App\Http\Response;
use App\Http\Services\SettlementService;
use Illuminate\Http\Request;

class TestController extends Controller
{



    public function test(Request $request)
    {



        // $str="1,4,5,10,11,13,20,27,30,32,33,36,40,47,54,59,61,64,67,79";
        // $nums= array_map('intval', explode(',', $str));
        // $logic =new LotteryStakeLogic(48,$nums);
        // $logic->total_double_sided($nums,);

        $service =new SettlementService();
        $data=[];
        $data['type']=48;
        $data['period']=1123486;
        $service->calcLotteryResult($data);



        

        
    }
    //
}
