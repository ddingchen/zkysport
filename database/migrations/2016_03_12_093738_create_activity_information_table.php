<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_information', function (Blueprint $table) {
            $table->increments('id');
            $table->string('realname');
            $table->string('tel');
            $table->integer('sub_district_id')->unsigned();
            $table->integer('housing_estate_id')->unsigned();
            $table->timestamps();

            $table->foreign('sub_district_id')->references('id')->on('sub_districts')->onDelete('cascade');
            $table->foreign('housing_estate_id')->references('id')->on('housing_estates')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_information');
    }
}
