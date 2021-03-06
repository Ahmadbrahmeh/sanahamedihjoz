<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cash', function (Blueprint $table) {
            $table->id();
            $table->foreignId("payment_id")->constrained();
            $table->float("amount");
            $table->float("net_amount");
            $table->float("exhange_rate");
            $table->foreignId("currency_id")->constrained(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_cash');
    }
}
