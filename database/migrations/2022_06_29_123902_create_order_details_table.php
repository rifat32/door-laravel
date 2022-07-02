<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // $table->unsignedBigInteger("variation_id");
            // $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->string("price");
            $table->string("qty");
            $table->unsignedBigInteger("product_id");
            $table->string("coupon_discount_type")->nullable();
            $table->string("coupon_discount_amount")->nullable();

            $table->unsignedBigInteger("selectedHeight")->nullable();
            $table->unsignedBigInteger("selectedWidth")->nullable();
            $table->string("selectedProductColor")->nullable();

            $table->boolean("is_hinge_holes")->nullable();
            $table->boolean("is_custom_size")->nullable();
            $table->boolean("is_extra_holes")->nullable();
            $table->unsignedBigInteger("orientation_id")->nullable();
            $table->string("hinge_holes_from_top")->nullable();
            $table->string("hinge_holes_from_bottom")->nullable();
            $table->unsignedBigInteger("extra_holes_direction_id")->nullable();
            $table->string("extra_holes_value")->nullable();
            $table->string("custom_height")->nullable();
            $table->string("custom_width")->nullable();
            $table->string("selected_length")->nullable();


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
        Schema::dropIfExists('order_details');
    }
}
