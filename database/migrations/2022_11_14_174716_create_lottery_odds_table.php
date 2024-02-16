<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryOddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_odds', function (Blueprint $table) {
            $table->id();
            $table->integer("type");
            $table->integer("stake_id");
            $table->integer("red_limit")->nullable();
            $table->string("odds", 1000)->nullable();
            $table->unique(["type", "stake_id"], 'TS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_odds');
    }
}
