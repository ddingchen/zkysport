<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingTimeSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_time_selects', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('sport_id')->unsigned();
            $table->time('start_from');
            $table->time('end_from');
            $table->tinyInteger('sort');
            $table->timestamps();

            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_time_selects');
    }
}
