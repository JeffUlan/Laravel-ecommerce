<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('sku')->unique();
            $table->string('type');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('product_relations', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned();
            $table->integer('child_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_up_sells', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned();
            $table->integer('child_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_cross_sells', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned();
            $table->integer('child_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
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

        Schema::dropIfExists('product_categories');
    }
}
