<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId("organization_id")->constrained();
            $table->boolean("saturday")->default(false);
            $table->boolean("sunday")->default(false);
            $table->boolean("monday")->default(false);
            $table->boolean("tuesday")->default(false);
            $table->boolean("wednesday")->default(false);
            $table->boolean("thursday")->default(false);
            $table->boolean("friday")->default(false);
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
        Schema::dropIfExists('weekdays');
    }
} 