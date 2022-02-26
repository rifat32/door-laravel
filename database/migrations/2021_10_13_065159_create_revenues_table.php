<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer("amount");
            $table->string("account_number");
            $table->string("customer");
            $table->text("description");
            $table->string("category");
            $table->string("reference");
            $table->integer("wing_id");
            $table->integer("bank_id");
            $table->integer("transaction_id")->nullable();
            $table->boolean("status")->default(false);

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
        Schema::dropIfExists('revenues');
    }
}
