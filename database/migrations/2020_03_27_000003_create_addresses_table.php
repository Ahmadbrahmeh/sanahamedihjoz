<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("code")->unique();
            $table->integer("parent_id")->nullable();
            $table->string("type");
            $table->boolean('mark_for_delete')->default(0);
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
        Schema::dropIfExists('addresses');
    }

    public function insertTestData() {
        DB::table('addresses')->insert(
            array(
                array(
                    'name' => 'نابلس',
                    'code' => 'NBS',
                    'type' => 'city',
                    'parent_id' => 0
                ), 
                array(
                    'name' => 'رفيديا',
                    'code' => 'RFD',
                    'type' => 'region',
                    'parent_id' => 1
                ), 
                array(
                    'name' => 'منطقة الجامعة',
                    'code' => 'NJH',
                    'type' => 'street',
                    'parent_id' => 2
                ), 
                array(
                    'name' => 'شارع الجنيد',
                    'code' => 'JND',
                    'type' => 'street',
                    'parent_id' => 2
                ),
                array(
                    'name' => 'رام الله',
                    'code' => 'RAM',
                    'type' => 'city',
                    'parent_id' => 0
                ), 
                array(
                    'name' => 'الخليل',
                    'code' => 'hebron',
                    'type' => 'city',
                    'parent_id' => 0
                ), 
            )
        );
    }
}
