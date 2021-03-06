<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPaymentCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_payment_cash', function (Blueprint $table) {
            $table->id();
            $table->foreignId("receipt_payment_id")->on('receipt_payments');
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
        Schema::dropIfExists('receipt_payment_cash');
    }
}
