<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("organization_id")->constrained();   
            $table->foreignId("customer_id")->nullable()->unsigned()->constrained();
            $table->foreignId("supplier_id")->nullable()->unsigned()->constrained();
            $table->foreignId("employee_id")->nullable()->unsigned()->constrained();
            $table->string("client_type");
            $table->string("invoice_number");
            $table->string("part_number");
            $table->string("receipt_date");
            $table->float("total");
            $table->float("total_cash")->default(0);
            $table->float("total_cheque")->default(0);
            $table->string("type")->nullable();
			$table->string("notes")->nullable();
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
        Schema::dropIfExists('receipts');
    }
}
