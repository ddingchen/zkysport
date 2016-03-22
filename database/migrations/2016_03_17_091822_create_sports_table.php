<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('require_number_of_people');
            $table->enum('area_choose_type', ['chart', 'list']);
            $table->enum('booking_time_type', ['period', 'hourly', 'none']);
            $table->enum('booking_date_after', ['today', 'tomorrow']);
            $table->timestamps();

            // $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sports');
    }
}
