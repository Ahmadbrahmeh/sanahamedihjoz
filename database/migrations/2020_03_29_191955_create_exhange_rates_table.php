<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhange_rates', function (Blueprint $table) {
            $table->id();           
            $table->foreignId("from");
            $table->foreignId("to");                
            $table->foreign('from')->references('id')->on('currencies');
            $table->foreign('to')->references('id')->on('currencies');
            $table->decimal("value", 8, 2);
            $table->boolean("default")->default(false);
            $table->foreignId("created_by");
            $table->foreignId("updated_by");      
            $table->foreignId("organization_id")->constrained();  
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
        Schema::dropIfExists('exhange_rates');
    }
}
