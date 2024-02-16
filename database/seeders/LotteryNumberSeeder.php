<?php

namespace Database\Seeders;

use App\Models\LotteryNumber;
use Illuminate\Database\Seeder;

class LotteryNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        LotteryNumber::truncate();
        $numbers = [
            ["period"=>1123466,"numbers"=>"1,3,32,22,34,17","open_time"=>"2022-12-24 12:12:12","type"=>48],
            ["period"=>1123486,"numbers"=>"1,3,32,22,34,17","open_time"=>"2022-12-24 12:12:12","type"=>48],
            ["period"=>1123446,"numbers"=>"1,3,32,22,34,17","open_time"=>"2022-12-24 12:12:12","type"=>48]
        ];

        LotteryNumber::insert($numbers);
    }
}
