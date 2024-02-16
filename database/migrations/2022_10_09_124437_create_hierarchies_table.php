<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHierarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hierarchies', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->string('name');
            $table->string('coin_type')->nullable();
            $table->tinyInteger('commercial_mode')->nullable();
            $table->tinyInteger('level');
            $table->string('desc')->nullable();
            $table->integer('root_parent'); //根上級id
            $table->integer('parent'); //上級id
            $table->string('path')->nullable(); //層級路徑
            $table->float('balance', 10, 2)->nullable();
            $table->string('balance_desc')->nullable();
            $table->float('occupy_percent', 10, 2)->nullable();
            $table->string("occupy_desc")->nullable();
            $table->float('water_occupy', 10, 2)->nullable();
            $table->float('water_percent', 10, 2)->nullable();
            $table->string("water_desc")->nullable();
            $table->string("phone")->nullable();
            $table->tinyInteger("freeze_status")->nullable();
            $table->tinyInteger("has_test_account")->nullable();
            $table->timestamp("last_login")->nullable();
            $table->unique("account", 'UN');
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
        Schema::dropIfExists('hierarchies');
    }
}
