<?php

namespace Database\Seeders;

use App\Models\Commercial;
use Illuminate\Database\Seeder;

class CommercialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commercial = [
            ["name" => "信用"],
            ["name" => "現金"],
        ];
        Commercial::insert($commercial);
    }
}
