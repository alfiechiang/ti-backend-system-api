<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        for ($i = 0; $i < 30; $i++) {
            $coin_names = ["VIETNAM_COIN", "KOREA_COIN"];
            $num = rand(0, 1);
            Machine::create([
                "video_address" => "xxxxxxxxx",
                "link_address" => "xxxxxxxxxx",
                "open" => true,
                "machine_model" => "XS-000$i",
                "machine_name" => "傲鷹$i" . "號",
                "coin_name" => $coin_names[$num],
            ]);
        }
    }
}
