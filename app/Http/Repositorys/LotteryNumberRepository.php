<?php
namespace App\Http\Repositorys;

use App\Models\LotteryNumber;
use Illuminate\Support\Facades\DB;

class LotteryNumberRepository
{

    public function typeLotteryNumber($data){

        $Builder = new LotteryNumber();
        if (isset($data['type'])){
            $Builder= $Builder->where('type',$data['type']);

        }

        if (isset($data['starttime'])&&isset($data['endtime'])){
            $Builder= $Builder->where('open_time',">=",$data['starttime']);
            $Builder= $Builder->where('open_time',"<=",$data['endtime']);
        }

        $res = $Builder->orderBy('open_time', 'desc')->get();
        return $res;
    }

    public function findOne($data){

        $Builder = new LotteryNumber();
        if (isset($data['type'])){
            $Builder= $Builder->where('type',$data['type']);
        }

        if (isset($data['period'])){
            $Builder= $Builder->where('period',$data['period']);
        }
        $res =$Builder->first();
        return $res;

    }


}
