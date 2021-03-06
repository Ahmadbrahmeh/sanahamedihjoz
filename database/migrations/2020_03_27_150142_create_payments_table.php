<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("organization_id")->constrained();  
            $table->foreignId("reservation_id")->constrained();
            $table->string("invoice_number");
            $table->string("part_number");
            $table->float("total");
            $table->float("total_cash")->default(0);
            $table->float("total_cheque")->default(0);
            $table->string("type")->nullable();
            $table->foreignId("created_by");
            $table->foreignId("updated_by");      
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
