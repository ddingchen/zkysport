<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('sport_id')->unsigned();
            $table->string('name');
            $table->string('tel');
            $table->tinyInteger('number_of_people')->nullable();
            $table->datetime('start_from')->nullable();
            $table->datetime('end_to')->nullable();
            $table->integer('booking_area_select_id')->unsigned();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sport_id')->references('id')->on('sports');
            $table->foreign('booking_area_select_id')->references('id')->on('booking_area_selects');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_orders');
    }
}
