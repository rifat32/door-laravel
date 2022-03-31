<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonHoldingCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_holding_citizens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("ward_id");
            $table->unsignedBigInteger("village_id");
            $table->unsignedBigInteger("post_office_id");
            $table->unsignedBigInteger("upazila_id");
            $table->unsignedBigInteger("district_id");



            $table->string("institute_name");
            $table->string("business_address");
            $table->string("license_no");
            $table->string("license_user_name");
            $table->string("guardian");
            $table->string("mother_name");
            $table->string("nid");
            $table->string("mobile");
            $table->string("parmanent_address");
            $table->string("type");
            $table->string("current_year");
            $table->float("tax_amount");
            $table->float("previous_due");
            $table->string("holding_no");


            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
            $table->foreign('post_office_id')->references('id')->on('post_offices')->onDelete('cascade');
            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');



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
        Schema::dropIfExists('non_holding_citizens');
    }
}
