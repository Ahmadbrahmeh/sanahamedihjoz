<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_terms', function (Blueprint $table) {
            $table->id();
            $table->integer("sequence");
            $table->string("value");
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
        Schema::dropIfExists('reservation_terms');
    }
}
