<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string("billing_address");
            $table->string("billing_address2")->nullable();
            $table->string("city");
            $table->string("zipcode");
            $table->unsignedBigInteger("user_id");
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->string("fname");
             $table->string("lname");
             $table->string("cname");
             $table->string("country");
             $table->string("state");
             $table->string("phone");
             $table->boolean("is_default");


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
        Schema::dropIfExists('addresses');
    }
}
