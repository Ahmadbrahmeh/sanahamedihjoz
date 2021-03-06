<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationHalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_halls', function (Blueprint $table) {
            $table->id();
            $table->foreignId("hall_id")->constrained();
            $table->float("cost_per_person")->default(0);
            $table->integer("persons")->default(0);
            $table->float("cost")->default(0);
            $table->string("note")->nullable();
            $table->foreignId("reservation_id")->constrained();
            $table->boolean("mark_for_delete")->default(false);
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
        Schema::dropIfExists('reservation_halls');
    }
}
