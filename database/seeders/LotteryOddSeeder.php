<?php

namespace Database\Seeders;

use App\Models\Lottery;
use App\Models\LotteryOdd;
use Illuminate\Database\Seeder;

class LotteryOddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LotteryOdd::truncate();
        $lotterys = Lottery::all();
        $stakes = [
            ["stake_id" => 1, "odds" => 2.43, "red_limit" => 1000],
            ["stake_id" => 2, "odds" => 4.77, "red_limit" => 1000],
            ["stake_id" => 3, "odds" => 2.43, "red_limit" => 1000],
            ["stake_id" => 4, "odds" => 2.43, "red_limit" => 1000],
            ["stake_id" => 5, "odds" => 4.77, "red_limit" => 1000],
            ["stake_id" => 6, "odds" => 2.43, "red_limit" => 1000],
            ["stake_id" => 7, "odds" => 2.43, "red_limit" => 1000],
            ["stake_id" => 8, "odds" => 1.98, "red_limit" => 1000],
            ["stake_id" => 9, "odds" => 1.98, "red_limit" => 1000],
            ["stake_id" => 10, "odds" => 1.98, "red_limit" => 1000],
            ["stake_id" => 11, "odds" => 3.8, "red_limit" => 1000],
            ["stake_id" => 12, "odds" => 3.8, "red_limit" => 1000],
            ["stake_id" => 13, "odds" => 3.8, "red_limit" => 1000],
            ["stake_id" => 14, "odds" => 3.8, "red_limit" => 1000],
            ["stake_id" => 15, "odds" => 1.98, "red_limit" => 1000],
            ["stake_id" => 16, "odds" => 1.98, "red_limit" => 1000],
            ["stake_id" => 17, "odds" => 1, "red_limit" => 1000],
            ["stake_id" => 18, "odds" => 1, "red_limit" => 1000],
            ["stake_id" => 19, "odds" => 1, "red_limit" => 1000],
            ["stake_id" => 20, "odds" => 1, "red_limit" => 1000],
            ["stake_id" => 21, "odds" => 1, "red_limit" => 1000],


        ];
        $inserData = [];
        foreach ($lotterys as $lottery) {
            foreach ($stakes as $stake) {
                $info = [];
                $info['type'] = $lottery->type;
                $info['stake_id'] = $stake['stake_id'];
                $info['odds'] = $stake['odds'];
                $info['red_limit'] = $stake['red_limit'];
                $inserData[] = $info;
            }
        }

        LotteryOdd::insert($inserData);
    }
}
