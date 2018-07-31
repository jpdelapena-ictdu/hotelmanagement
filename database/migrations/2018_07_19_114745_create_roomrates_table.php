<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomrates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rate_id');
            $table->string('board_id');
            $table->string('roomtype_id');
            $table->string('price');
            $table->string('inclusions')->nullable();
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
        Schema::dropIfExists('roomrates');
    }
}
