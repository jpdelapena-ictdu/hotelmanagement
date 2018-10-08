<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservation_code');
            $table->integer('customer_id');
            $table->integer('roomtype_id');
            $table->integer('room_id');
            $table->integer('rate_id');
            $table->date('arrival');
            $table->date('departure');
            // $table->integer('adults')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('discount')->nullable();
            // $table->boolean('early_checkin')->nullable();
            // $table->boolean('late_checkout')->nullable();
            $table->text('notes')->nullable();
            $table->text('additional_information')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
