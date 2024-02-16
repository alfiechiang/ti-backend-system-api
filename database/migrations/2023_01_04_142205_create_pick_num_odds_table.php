<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickNumOddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_num_odds', function (Blueprint $table) {
            $table->id();
            $table->integer("type")->comment("彩票ID");
            $table->tinyInteger("zhung")->comment("中幾顆球");
            $table->tinyInteger("option")->comment("選幾顆球");
            $table->string("odds", 1000)->nullable();
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
        Schema::dropIfExists('pick_num_odds');
    }
}
