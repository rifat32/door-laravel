<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_options', function (Blueprint $table) {
            $table->id();

             $table->unsignedBigInteger("option_id");
             $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');


             $table->unsignedBigInteger("option_value_id");
             $table->foreign('option_value_id')->references('id')->on('option_values')->onDelete('cascade');


             $table->unsignedBigInteger("order_detail_id");
             $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');


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
        Schema::dropIfExists('order_detail_options');
    }
}
