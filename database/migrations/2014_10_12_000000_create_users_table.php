<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("fname");
            $table->string("lname");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("path")->nullable();
            $table->string("temp_password")->nullable();
            $table->boolean("first_login")->default(1);
            $table->boolean("isAdmin")->default(0);
            $table->boolean("mark_for_delete")->default(false);
            $table->timestamps();
        });
        $this->insertTestData();
    }

    public function insertTestData(){
        DB::table('users')->insert(
            array(
                array(
                    'fname' => 'ربيع',
                    'lname' => 'دراغمة',
                    'email' => 'road@gmail.com',
                    'password' => Hash::make('123456789'),
                    'first_login' => false
                )
            )  
        );

        DB::table('users')->insert(
            array(
                array(
                    'fname' => 'الادمن',
                    'lname' => 'الادمن',
                    'email' => 'admin@gmail.com',
                    'isAdmin' => true,
                    'password' => Hash::make('123456789'),
                    'first_login' => false
                )
            )  
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
