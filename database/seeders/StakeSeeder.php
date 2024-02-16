<?php

namespace Database\Seeders;

use App\Models\Stake;
use Illuminate\Database\Seeder;

class StakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Stake::truncate();
        $stakes = [
            ["stake_id"=>1,"stake_name"=>"上"],
            ["stake_id"=>2,"stake_name"=>"中"],
            ["stake_id"=>3,"stake_name"=>"下"],
            ["stake_id"=>4,"stake_name"=>"奇"],
            ["stake_id"=>5,"stake_name"=>"和"],
            ["stake_id"=>6,"stake_name"=>"偶"],
            ["stake_id"=>7,"stake_name"=>"大"],
            ["stake_id"=>8,"stake_name"=>"小"],
            ["stake_id"=>9,"stake_name"=>"單"],
            ["stake_id"=>10,"stake_name"=>"雙"],
            ["stake_id"=>11,"stake_name"=>"大單"],
            ["stake_id"=>12,"stake_name"=>"小單"],
            ["stake_id"=>13,"stake_name"=>"大雙"],
            ["stake_id"=>14,"stake_name"=>"小雙"],
            ["stake_id"=>15,"stake_name"=>"指數-大"],
            ["stake_id"=>16,"stake_name"=>"指數-小"],
            ["stake_id"=>17,"stake_name"=>"金"],
            ["stake_id"=>18,"stake_name"=>"木"],
            ["stake_id"=>19,"stake_name"=>"水"],
            ["stake_id"=>20,"stake_name"=>"火"],
            ["stake_id"=>21,"stake_name"=>"土"],

        ];

        Stake::insert($stakes);
    }
}
