<?php

namespace Database\Seeders;

use App\Models\Lottery;
use App\Models\PickNumOdd;
use Illuminate\Database\Seeder;

class PickNumOddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        PickNumOdd::truncate();
        $lotterys = Lottery::all();
        $insertData = [];
        foreach($lotterys as $lottery){
            for ($i=1;$i<=5;$i++){
                for ($k=1;$k<=5;$k++){
                    $odd = [];
                    $odd['type'] = $lottery->type;
                    $odd['zhung'] = $i;

                    $odd['option'] = $k;
                    $odd['odds'] = 2;
                    $insertData[] = $odd;
                }
            }
        }

        PickNumOdd::insert($insertData);

    }


}
