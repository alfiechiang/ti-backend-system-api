<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberGameStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_game_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer("game_type");
            $table->string("game_name");
            $table->integer("member_id");
            $table->tinyInteger("open")->default(1);
            $table->unique(["member_id", "game_name"], 'MG');
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
        Schema::dropIfExists('member_game_statuses');
    }
}
