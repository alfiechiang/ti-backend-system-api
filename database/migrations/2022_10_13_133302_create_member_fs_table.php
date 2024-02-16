<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_fs', function (Blueprint $table) {
            $table->id();

            $table->string('account');
            $table->string('parent_account')->nullable();
            $table->integer('game_type')->nullable();
            $table->integer('game_id')->nullable();
            $table->float('total_bet', 10, 2)->default(0);
            $table->float('valid_bet', 10, 2)->default(0);
            $table->float('result', 10, 2)->default(0);
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
        Schema::dropIfExists('member_fs');
    }
}
