<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('category')->comment('三级分类ID');
            $table->string('category_name')->comment('三级分类名');
            $table->string('sku')->comment('SKU');
            $table->string('module')->comment('型号');
            $table->string('standard')->comment('规格');
            $table->decimal('price', 10, 2)->comment('价格');
            $table->unsignedInteger('reserve')->comment('库存');
            $table->json('images')->comment('缩略图');
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
        Schema::dropIfExists('product_items');
    }
}
