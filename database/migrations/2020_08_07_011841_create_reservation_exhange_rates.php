<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationExhangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_exhange_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("from");
            $table->foreignId("to");                
            $table->foreign('from')->references('id')->on('currencies');
            $table->foreign('to')->references('id')->on('currencies');
            $table->decimal("value", 8, 2);
            $table->foreignId("reservation_id")->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_exhange_rates');
    }
}
