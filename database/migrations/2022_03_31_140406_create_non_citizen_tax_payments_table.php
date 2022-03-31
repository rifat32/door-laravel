<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonCitizenTaxPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_citizen_tax_payments', function (Blueprint $table) {
            $table->id();
            $table->date("payment_for");
            $table->float("amount");
            $table->float("due");
            $table->string("current_year");

            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("non_citizen_id");
            $table->unsignedBigInteger("method_id");


            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('method_id')->references('id')->on('methods')->onDelete('cascade');

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
        Schema::dropIfExists('non_citizen_tax_payments');
    }
}
