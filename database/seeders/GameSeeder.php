<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Game::truncate();

        Game::create([
            "name" => "slot",
            "ch_name" => "電子機台"
        ]);
        Game::create([
            "name" => "lottery",
            "ch_name" => "彩票"
        ]);
        Game::create([
            "name" => "bt",
            "ch_name" => "bt電子老虎機"
        ]);

    }
}
