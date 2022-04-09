<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationValueTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_value_templates', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("variation_template_id");
            $table->foreign('variation_template_id')->references('id')->on('variation_templates')->onDelete('cascade');
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
        Schema::dropIfExists('variation_value_templates');
    }
}
