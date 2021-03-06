<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->nullable();
            $table->string("phone1");
            $table->string("phone2")->nullable();
            $table->string("phone3")->nullable();
            $table->string("certifcate")->nullable();
            $table->foreignId("organization_id")->constrained();
            $table->foreignId("created_by");
            $table->foreignId("updated_by");
            $table->foreignId("address_id")->constrained();
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
        Schema::dropIfExists('suppliers');
    }
}
