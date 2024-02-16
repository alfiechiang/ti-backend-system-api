<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotReocrdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_reocrds', function (Blueprint $table) {
            $table->id();
            $table->string("account")->comment("會員帳號");
            $table->timestamp('open_score_time', $precision = 0)->nullable()->comment("開分時間");
            $table->timestamp('wash_score_time', $precision = 0)->nullable()->comment("洗分時間");
            $table->string("machine_model")->comment("機台型號");
            $table->string("machine_name")->comment("機台名稱");
            $table->integer("open_score")->comment("開分");
            $table->integer("wash_score")->comment("洗分");
            $table->integer("result")->comment("輸贏");
            $table->boolean("caculate")->default(true)->comment("1為列入計算 0為否");
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
        Schema::dropIfExists('slot_reocrds');
    }
}
