<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payments', function (Blueprint $table) {
            $table->id();

            $table->date("payment_for");
            $table->float("amount");
            $table->string("current_year");

            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("citizen_id");
            $table->unsignedBigInteger("method_id");


            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('citizen_id')->references('id')->on('citizens')->onDelete('cascade');
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
        Schema::dropIfExists('tax_payments');
    }
}
