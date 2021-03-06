<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventlist', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("organization_id")->constrained();  
            $table->boolean('mark_for_delete')->default(0);
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
        Schema::dropIfExists('eventlist');
    }
}
