<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_rule_id')->unsigned()->unique();
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('act_operator')->default('fixed');
            $table->decimal('act_amount', 12, 4)->default(0.0000);
            $table->unsignedSmallInteger('act_stop')->default(0);
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
        Schema::dropIfExists('catalog_products');
    }
}