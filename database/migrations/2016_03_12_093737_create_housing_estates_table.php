<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHousingEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('housing_estates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_district_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('sub_district_id')->references('id')->on('sub_districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('housing_estates');
    }
}
