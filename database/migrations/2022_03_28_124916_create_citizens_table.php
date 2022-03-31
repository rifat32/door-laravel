<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("ward_id");
            $table->unsignedBigInteger("village_id");
            $table->unsignedBigInteger("post_office_id");
            $table->unsignedBigInteger("upazila_id");
            $table->unsignedBigInteger("district_id");


            $table->string("holding_no");
            $table->string("thana_head_name");
            $table->string("thana_head_religion");
            $table->string("thana_head_gender");
            $table->string("thana_head_occupation");
            $table->string("mobile");
            $table->string("guardian");
            $table->string("c_mother_name");
            $table->string("nid_no");
            $table->boolean("is_tubewell");
            $table->string("latrin_type");
            $table->string("type_of_living");
            $table->string("type_of_organization");
            $table->float("previous_due");
            $table->float("tax_amount");
            $table->integer("male");
            $table->integer("female");
            $table->float("annual_price");
            $table->integer("gov_advantage");
            $table->string("image");
            $table->date("current_year");
            $table->float("raw_house");
            $table->float("half_building_house");
            $table->float("building_house");





            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
            $table->foreign('post_office_id')->references('id')->on('post_offices')->onDelete('cascade');
            $table->foreign('upazila_id')->references('id')->on('upazilas')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');







            $table->softDeletes();
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
        Schema::dropIfExists('citizens');
    }
}
