<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_numbers', function (Blueprint $table) {
            $table->string("period", 30);
            $table->string("numbers", 100);
            $table->tinyInteger("type");
            $table->primary("period");
            $table->unique(["type", "period"], 'UN');
            $table->timestamp('open_time', $precision = 0)->nullable()->comment("開獎時間");
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
        Schema::dropIfExists('lottery_numbers');
    }
}
