<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parchases', function (Blueprint $table) {
            $table->id();
            $table->string("payment_method");
            $table->string("account_number")->nullable();


            $table->string("supplier");
            $table->string("reference_no");
            $table->string("purchase_status");
            $table->integer("product_id");
            $table->integer("quantity");
            $table->unsignedBigInteger("transaction_id")->nullable();
            $table->boolean("is_active")->default(0);
            $table->unsignedBigInteger("user_id");

            $table->unsignedBigInteger("bank_id")->nullable();
            $table->unsignedBigInteger("wing_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign("bank_id")->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('wing_id')->references('id')->on('wings')->onDelete('cascade');


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
        Schema::dropIfExists('parchases');
    }
}
