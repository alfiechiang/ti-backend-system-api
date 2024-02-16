<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickNumLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_num_limits', function (Blueprint $table) {
            $table->id();
            $table->integer("type")->comment("彩票ID");
            $table->tinyInteger("option")->comment("選幾顆球");
            $table->integer("red_limit")->comment("限紅");
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
        Schema::dropIfExists('pick_num_limits');
    }
}
