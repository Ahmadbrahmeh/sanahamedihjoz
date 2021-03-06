<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("code")->nullable();
            $table->string("type")->nullable();
            $table->time("from_time", 0);
            $table->time("to_time", 0);
            $table->integer("prepare_duration")->default(0);
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
        Schema::dropIfExists('organizations');
    }

    public function insertTestData() {
        DB::table('organizations')->insert(
            array(
                array(
                    'name' => 'Super Admin',
                    'prepare_duration' => '0',
                    'type' => '0',
                    'code' => 'DSA',
                    'from_time' => date("H:i:s", strtotime("08:00:00")),
                    'to_time' => date("H:i:s", strtotime("24:00:00")),
                )
            )
        );
        
        DB::table('organizations')->insert(
            array(
                array(
                    'name' => 'صالة القلعة',
                    'prepare_duration' => '30',
                    'type' => '1',
                    'code' => 'DSA',
                    'from_time' => date("H:i:s", strtotime("08:00:00")),
                    'to_time' => date("H:i:s", strtotime("24:00:00")),
                )
            )
        );
    }
}
