<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("category_id")->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->boolean("is_all_category_product")->nullable();
            $table->string("code");
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->double("discount_amount");
            $table->dateTime("expire_date");
            $table->boolean("is_active");
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
        Schema::dropIfExists('coupons');
    }
}
