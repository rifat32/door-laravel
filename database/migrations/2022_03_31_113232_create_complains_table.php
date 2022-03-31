<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->string("complain_no");
            $table->string("year");
            $table->string("complain_date");
            $table->string("applicant_name");
            $table->string("applicant_father_name");
            $table->string("applicant_witness");
            $table->string("applicant_village");
            $table->string("applicant_post_office");
            $table->string("applicant_district");
            $table->string("applicant_thana");
            $table->string("defendant_name");
            $table->string("defendants");
            $table->string("defendant_father_name");
            $table->string("defendant_village");
            $table->string("defendant_post_office");
            $table->string("defendant_district");
            $table->string("defendant_thana");
            $table->string("applicant_mobile");
            $table->date("date");
            $table->string("time");
            $table->string("place");
            $table->string("is_solved");

            $table->unsignedBigInteger("union_id");
            $table->unsignedBigInteger("chairman_id");
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('chairman_id')->references('id')->on('chairmen')->onDelete('cascade');
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
        Schema::dropIfExists('complains');
    }
}
