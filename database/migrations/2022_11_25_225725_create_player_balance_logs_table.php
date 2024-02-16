<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerBalanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp("change_time")->nullable()->comment('時間');
            $table->string("account")->comment("帳號");
            $table->string("game_category")->comment("類型");
            $table->float('balance_before', 10, 2)->nullable()->comment("交易前餘額");
            $table->float('expense', 10, 2)->nullable()->comment("收入支出");
            $table->float('balance_after', 10, 2)->nullable()->comment("交易後餘額");
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
        Schema::dropIfExists('player_balance_logs');
    }
}
