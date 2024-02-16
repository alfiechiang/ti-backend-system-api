<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToSlotReocrdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slot_reocrds', function (Blueprint $table) {
            $table->tinyInteger("status")->after("caculate")->default(0)->comment("未結算0 已結算1 已取消2" );
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slot_reocrds', function (Blueprint $table) {
            //
        });
    }
}
