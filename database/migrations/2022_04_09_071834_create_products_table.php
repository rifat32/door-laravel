<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->enum('type', ['single', 'variable',  'panel']);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('style_id')->nullable();
            $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
            $table->string("sku")->unique();
            $table->string("image")->nullable();
            $table->string("description");
            $table->enum('status', ['draft', 'active',"inactive"]);
            $table->boolean("is_featured");

            $table->integer("length_lower_limit")->nullable();
            $table->integer("length_upper_limit")->nullable();
            $table->boolean("length_is_required")->default(0);


            $table->integer("height_lower_limit")->nullable();
            $table->integer("height_upper_limit")->nullable();
            $table->boolean("height_is_required")->default(0);

            $table->integer("width_lower_limit")->nullable();
            $table->integer("width_upper_limit")->nullable();
            $table->boolean("width_is_required")->default(0);

            $table->string('slug')->unique();
            $table->text('panels')->nullable();
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
        Schema::dropIfExists('products');
    }
}
