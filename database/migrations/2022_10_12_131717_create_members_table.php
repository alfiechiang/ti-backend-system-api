<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer("agent_id");
            $table->string("account");
            $table->string("name");
            $table->string("phone");
            $table->tinyInteger("freeze_status")->nullable();
            $table->tinyInteger("has_test_account")->nullable();
            $table->float('balance', 10, 2)->nullable();
            $table->string("balance_desc")->nullable();
            $table->string("desc")->nullable();
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
        Schema::dropIfExists('members');
    }
}
