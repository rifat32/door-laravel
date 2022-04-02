<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizenTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_taxes', function (Blueprint $table) {
            $table->id();
            $table->string("note");
            $table->float("amount");
            $table->string("current_year");


            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("citizen_id");
            $table->unsignedBigInteger("ward_id");


            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('citizen_id')->references('id')->on('citizens')->onDelete('cascade');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');

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
        Schema::dropIfExists('citizen_taxes');
    }
}
