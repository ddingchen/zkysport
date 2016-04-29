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
            $table->string('sn', 15);
            $table->integer('user_id')->unsigned();
            $table->string('sport');
            $table->string('name');
            $table->string('tel');
            $table->tinyInteger('number_of_people')->nullable();
            $table->date('use_at');
            $table->time('start_from');
            $table->time('end_to');
            $table->string('areas');
            $table->integer('payment_id')->unsigned()->nullable();
            $table->float('origin_amount');
            $table->datetime('expired_at')->nullable();
            $table->boolean('cancel');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
