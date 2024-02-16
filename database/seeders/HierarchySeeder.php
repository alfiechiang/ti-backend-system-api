<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Hierarchy;
use App\Models\Machine;
use App\Models\Member;
use App\Models\MemberGameStatus;
use App\Models\SlotReocrd;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 2; $i++) {
            $level = 4;
            $account = Str::random(4);
            $comapny = Hierarchy::create([
                "account" => $account,
                "name" => Str::random(4),
                "coin_type" => 1,
                "commercial_mode" => 1,
                "level" => 4,
                "desc" => "xxxxx",
                "root_parent" => 0,
                "parent" => 0,
                "balance" => 100000,
                "balance_desc" => "xxxxx",
                "occupy_percent" => 20,
                "occupy_desc" => "xxxxx",
                "water_occupy" => 20,
                "water_percent" => 20,
                "water_desc" => "xxxxxxx",
                "phone" => "12345678",
                "freeze_status" => 0,
                "has_test_account" => 1,

            ]);
            User::create([
                "account" => $account,
                "password" => bcrypt("aa1234"),
                "role_id" => 1
            ]);



            if ($level == 4) {
                $comapny->root_parent = 0;
                $comapny->parent = 0;
                $comapny->path = $comapny->id;
                $comapny->save();
            }

            for ($i = 0; $i < 1; $i++) {
                $this->mock_station($comapny->id);
            }
        }
    }



    private function mock_station($comapny_id)
    {


        $account = Str::random(4);
        $tc = Hierarchy::create([
            "account" => $account,
            "name" => Str::random(4),
            "coin_type" => 1,
            "commercial_mode" => 1,
            "level" => 3,
            "desc" => "xxxxx",
            "root_parent" => $comapny_id,
            "parent" => $comapny_id,
            "balance" => 100000,
            "balance_desc" => "xxxxx",
            "occupy_percent" => 20,
            "occupy_desc" => "xxxxx",
            "water_occupy" => 20,
            "water_percent" => 20,
            "water_desc" => "xxxxxxx",
            "phone" => "12345678",
            "freeze_status" => 0,
            "has_test_account" => 1,

        ]);

        $h = Hierarchy::find($comapny_id);
        $path = $h->path . "," . $tc->id;

        $tc->path = $path;
        $tc->save();

        // $this->mock_stationFs($h, $tc);

        User::create([
            "account" => $account,
            "password" => bcrypt("aa1234"),
            "role_id" => 2
        ]);



        for ($i = 0; $i < 1; $i++) {
            $this->mock_agent($tc->id, $comapny_id);
        }
    }

    private function mock_agent($station_id, $comapny_id)
    {


        $account = Str::random(4);

        $tc = Hierarchy::create([
            "account" => $account,
            "name" => Str::random(4),
            "coin_type" => 1,
            "commercial_mode" => 1,
            "level" => 2,
            "desc" => "xxxxx",
            "root_parent" => $comapny_id,
            "parent" => $station_id,
            "balance" => 100000,
            "balance_desc" => "xxxxx",
            "occupy_percent" => 20,
            "occupy_desc" => "xxxxx",
            "water_occupy" => 20,
            "water_percent" => 20,
            "water_desc" => "xxxxxxx",
            "phone" => "12345678",
            "freeze_status" => 0,
            "has_test_account" => 1,

        ]);

        $h = Hierarchy::find($station_id);
        $path = $h->path . "," . $tc->id;

        $tc->path = $path;
        $tc->save();

        // $this->mock_agentFs($h, $tc);

        User::create([
            "account" => $account,
            "password" => bcrypt("aa1234"),
            "role_id" => 3
        ]);


        for ($i = 0; $i < 4; $i++) {
            $this->mock_member($tc->id);
        }
    }

    private function mock_member($agent_id)
    {

        $account = Str::random(4);
        $member = Member::create([
            "agent_id" => $agent_id,
            "account" => $account,
            "name" => Str::random(4),
            "phone" => "09234666789",
            "freeze_status" => 0,
            "has_test_account" => 1,
            "balance" => 1000,
            "balance_desc" => "xxxxxxx",
            "desc" => "XXXXXX",
        ]);

        $agent = Hierarchy::find($agent_id);


        // $this->mock_memberFs($member, $agent->account);


        User::create([
            "account" => $account,
            "password" => bcrypt("aa1234"),
            "role_id" => 4
        ]);

        $games = Game::all();
        foreach ($games as $game) {

            MemberGameStatus::create([
                "game_type" => $game->game_type,
                "game_name" => $game->name,
                "member_id" => $member->id,
                "open" => 1
            ]);
        }

        $m1 = Machine::find(1);
        SlotReocrd::create([
            "account" => $member->account,
            "open_score_time" => date('y-m-d h:i:s'),
            "wash_score_time" => date('y-m-d h:i:s'),
            "machine_model" => $m1->machine_model,
            "machine_name" => $m1->machine_name,
            "open_score" => 12000,
            "wash_score" => 1000,
            "result" => 1000,
        ]);

        // $m2 = Machine::find(2);

        // SlotReocrd::create([
        //     "account" => $member->account,
        //     "open_score_time" => date('d-m-y h:i:s'),
        //     "wash_score_time" => date('d-m-y h:i:s'),
        //     "machine_model" => $m2->machine_model,
        //     "machine_name" => $m2->machine_name,
        //     "open_score" => 12000,
        //     "wash_score" => 1000,
        //     "result" => 1000,
        // ]);
    }
}
