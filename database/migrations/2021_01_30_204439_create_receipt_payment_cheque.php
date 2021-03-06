<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPaymentCheque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_payment_cheque', function (Blueprint $table) {
            $table->id();
            $table->foreignId("receipt_payment_id")->on('receipt_payments');
            $table->float("amount");
            $table->float("net_amount");
            $table->float("exhange_rate");
            $table->string("cheque_num");
            $table->string("bank_account");
            $table->string("bank_name");
            $table->string("bank_branch")->nullable();
            $table->string("cheque_date");
            $table->string("note")->nullable();
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
        Schema::dropIfExists('receipt_payment_cheque');
    }
}
