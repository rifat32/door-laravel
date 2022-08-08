<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("fname");
            $table->string("lname");
            $table->string("cname")->nullable();
            $table->string("phone");
            $table->string("email");





            $table->string("customer_id");
            $table->string("billing_address");
            $table->string("billing_address2")->nullable();
            $table->string("city");
            $table->string("zipcode");




            $table->string("additional_info")->nullable();
            $table->string("payment_option");



            $table->string("coupon_id")->nullable();
            $table->string("coupon_discount_type")->nullable();
            $table->string("coupon_discount_amount")->nullable();


            $table->string("status")->default("Pending");

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
        Schema::dropIfExists('orders');
    }
}
