<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('out_trade_no', 32);
            $table->float('amount');
            $table->integer('user_id')->unsigned();
            $table->enum('product', ['activity', 'vip_charge', 'vip_buy', 'vip_promotion', 'vip_qr_consume']);
            $table->boolean('paid');
            $table->dateTime('paid_at')->nullable();
            $table->integer('vip_id')->nullable();
            $table->boolean('refund');
            $table->dateTime('refund_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
