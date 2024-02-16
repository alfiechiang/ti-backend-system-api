<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_records', function (Blueprint $table) {
            $table->id();
            $table->string("account")->comment("會員帳號");
            $table->integer("type");
            $table->integer("period");
            $table->integer("stake_id");
            $table->string("stake_name");
            $table->float('odds', 10, 2)->default(0)->comment("賠率");
            $table->float('bet_money', 10, 2)->default(0);
            $table->tinyInteger("status")->comment("為結算0 已結算1 已取消2" );
            $table->float('result', 10, 2)->default(0)->comment("輸贏");
            $table->timestamp('bet_time', $precision = 0)->nullable()->comment("投注時間");
            $table->softDeletes();
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
        Schema::dropIfExists('lottery_records');
    }
}
