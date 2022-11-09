<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string("checkout_session_id");
            $table->string("payer_email");
            $table->float("amount", 10, 2);
            $table->string("currency");
            $table->unsignedBigInteger("order_id");
            $table->string("payment_intent");
            $table->string("receipt_url");
            $table->string("status");
            $table->string("payment_gateway_name");
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
        Schema::dropIfExists('payments');
    }
};
