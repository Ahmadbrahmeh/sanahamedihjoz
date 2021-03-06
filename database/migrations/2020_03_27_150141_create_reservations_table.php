<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("part_number");
            $table->string("title");
            $table->string("customer_name");
            $table->foreignId("customer_id")->constrained();
            $table->string("status")->default("INITIAL_RESERVATION");
            $table->boolean("payment_status")->default(0);
            $table->foreignId("currency_id")->constrained(); 
            $table->float("total_cost")->default(0);
            $table->float("remaining_amount")->default(0);
            $table->float("deposit_amount")->default(0);
            $table->boolean("include_all_costs")->default(false);
            $table->foreignId("organization_id")->constrained();
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
        Schema::dropIfExists('reservations');
    }
}
