<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_fs', function (Blueprint $table) {
            $table->id();
            $table->string("parent_account");
            $table->string('account');
            $table->integer('game_type');
            $table->integer('game_id');
            $table->float('total_bet', 10, 2)->default(0);
            $table->float('valid_bet', 10, 2)->default(0);
            $table->float('result', 10, 2)->default(0);
            $table->float('uplevel_occupy_money', 10, 2)->default(0);

            $table->float('occupy_money', 10, 2)->default(0);
            $table->float('water_money', 10, 2)->default(0);
            $table->string("date")->comment("下注日期");
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
        Schema::dropIfExists('station_fs');
    }
}
