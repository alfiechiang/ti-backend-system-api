<?php

namespace Database\Seeders;

use App\Models\Lottery;
use App\Models\PickNumLimit;
use Illuminate\Database\Seeder;

class PickNumLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PickNumLimit::truncate();
        
        $lotterys = Lottery::all();
        $insetData = [];
        foreach($lotterys as $lottery){
            for ($i=1;$i<=5;$i++){
                $limit = [];
                $limit['type'] = $lottery->type;
                $limit['option'] = $i;
                $limit['red_limit'] = 1000;
                $insetData[] = $limit;
            }
        }

        PickNumLimit::insert($insetData);
    }
}
