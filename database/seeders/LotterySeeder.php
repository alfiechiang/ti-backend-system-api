<?php

namespace Database\Seeders;

use App\Models\Lottery;
use Illuminate\Database\Seeder;

class LotterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Lottery::truncate();
        
        $lotterys = [
            ["type"=>48,"lottery_name"=>"快樂8","open"=>true,"bet_time"=>60],
            ["type"=>34,"lottery_name"=>"台灣","open"=>true,"bet_time"=>60],
            ["type"=>47,"lottery_name"=>"越南","open"=>true,"bet_time"=>60],
            ["type"=>35,"lottery_name"=>"加拿大","open"=>true,"bet_time"=>60],
            ["type"=>36,"lottery_name"=>"加拿大西部","open"=>true,"bet_time"=>60],
            ["type"=>37,"lottery_name"=>"斯洛伐克","open"=>true,"bet_time"=>60],
            ["type"=>40,"lottery_name"=>"希臘","open"=>true,"bet_time"=>60],
            ["type"=>41,"lottery_name"=>"密西根","open"=>true,"bet_time"=>60],
            ["type"=>42,"lottery_name"=>"肯塔基","open"=>true,"bet_time"=>60],
            ["type"=>43,"lottery_name"=>"澳洲","open"=>true,"bet_time"=>60],
            ["type"=>46,"lottery_name"=>"奧勒岡","open"=>true,"bet_time"=>60],
        ];

        Lottery::insert($lotterys);
        
    }
}
