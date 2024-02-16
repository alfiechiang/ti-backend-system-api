<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerCoinEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_coin_events', function (Blueprint $table) {
            $table->id();
            $table->integer("member_id");
            $table->string("coin_name");
            $table->integer("coin_daily");
            $table->float('coin', 10, 2)->nullable();
            $table->boolean('stop');
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
        Schema::dropIfExists('player_coin_events');
    }
}
