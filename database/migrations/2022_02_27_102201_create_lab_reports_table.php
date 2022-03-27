<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();




            $table->date("date");
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("patient_id");
            $table->unsignedBigInteger("template_id");
            $table->longText("report");



            $table->foreign('template_id')->references('id')->on('lab_report_templates')->onDelete('cascade');

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');


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
        Schema::dropIfExists('lab_reports');
    }
}
