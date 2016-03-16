<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetailInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('information_id')->unsigned();

            $table->string('realname');
            $table->string('tel');
            $table->integer('sub_district_id')->unsigned();
            $table->integer('housing_estate_id')->unsigned();

            $table->timestamps();

            $table->foreign('information_id')->references('id')->on('informations')->onDelete('cascade');
            $table->foreign('sub_district_id')->references('id')->on('sub_districts');
            $table->foreign('housing_estate_id')->references('id')->on('housing_estates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detail_informations');
    }
}
