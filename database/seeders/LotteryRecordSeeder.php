<?php

namespace Database\Seeders;

use App\Models\Lottery;
use App\Models\LotteryOdd;
use App\Models\LotteryRecord;
use App\Models\Member;
use App\Models\Stake;
use Illuminate\Database\Seeder;

class LotteryRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LotteryRecord::truncate();
        $records = [];
        $members =Member::all();
        $lottery_odd = LotteryOdd::where("type", 48)->where("stake_id", 1)->first();


        $stake = Stake::where("stake_id", 1)->first();
        $stake2= Stake::where("stake_id", 2)->first();

        foreach($members as $member ){

            $record1 = [
                "account"=>$member->account,
                "type"=>$lottery_odd->type,
                "period"=>20211585,
                "stake_id"=>$lottery_odd->stake_id,
                "stake_name"=>$stake->stake_name,
                "odds"=>1.23,
                "bet_money"=>100,
                "status"=>true,
                "result"=>200,
                "bet_time"=>"2022-12-24 12:12:12"
            ];

            $record2 = [
                "account"=>$member->account,
                "type"=>$lottery_odd->type,
                "period"=>20211585,
                "stake_id"=>$lottery_odd->stake_id,
                "stake_name"=>$stake2->stake_name,
                "odds"=>1.23,
                "bet_money"=>100,
                "status"=>true,
                "result"=>200,
                "bet_time"=>"2022-12-24 12:12:12"
            ];

            $records[] = $record1;
            $records[] = $record2;


        }

       LotteryRecord::insert($records);


        
    }
}
