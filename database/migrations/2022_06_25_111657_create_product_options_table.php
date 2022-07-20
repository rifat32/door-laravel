<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("option_id");
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger("color_id")->nullable();
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');

            $table->boolean("is_required");
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
        Schema::dropIfExists('product_options');
    }
}
