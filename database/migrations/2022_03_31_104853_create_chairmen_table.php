<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChairmenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chairmen', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("nid");
            $table->string("mobile");
            $table->string("pro_image");
            $table->string("sign_image");
            $table->string("address");
            $table->unsignedBigInteger("union_id");
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('chairmen');
    }
}
