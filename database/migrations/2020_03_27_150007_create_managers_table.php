<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId("address_id")->constrained();
            $table->integer("type");
            $table->string("phone1");
            $table->string("phone2")->nullable();
            $table->string("phone3")->nullable();
            $table->string("fb_link")->nullable();
            $table->string("certifcate")->nullable();
            $table->foreignId("organization_id")->constrained();
            $table->timestamps();
        });

        $this->insertTestData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }

    public function insertTestData() {
        DB::table('managers')->insert(
            array(
                array(
                    'user_id' => 1,
                    'address_id' => 1,
                    'type' => 1,
                    'phone1' => '059412343',
                    'phone2' => '059812321',
                    'phone3' => '059912322',
                    'fb_link' => 'https://fb.com/samer',
                    'certifcate' => '1209310912090',
                    'organization_id' => 1
                )
            )
        );
    }
}
