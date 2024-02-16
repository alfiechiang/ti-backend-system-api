<?php

namespace Database\Seeders;

use App\Models\CoinEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoinEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        CoinEvent::truncate();
        $data = [
            ["name" => "VIETNAM_COIN", "ch_name" => "越南幣"],
            ["name" => "KOREA_COIN", "ch_name" => "韓國幣"]
        ];

        DB::table('coin_events')->insert($data);
    }
}
