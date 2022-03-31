<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_licenses', function (Blueprint $table) {
            $table->id();

            $table->string("institute");
            $table->string("owner");
            $table->string("guadian");
            $table->string("mother_name");
            $table->string("present_addess");
            $table->string("license_no");
            $table->string("business_type");
            $table->string("permanent_addess");
            $table->string("fee_des");
            $table->string("mobile_no");
            $table->float("fee");
            $table->float("vat");
            $table->string("nid");
            $table->date("expire_date");
            $table->float("total");
            $table->string("vat_des");
            $table->string("current_year");



            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("ward_id");


            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
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
        Schema::dropIfExists('trade_licenses');
    }
}
