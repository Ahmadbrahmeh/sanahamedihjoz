<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("type");
        });
        $this->insertData();
    }

    public function insertData() {
        DB::table('organization_types')->insert([
            ['name' => 'صالات', 'type' => '1']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_types');
    }
}
