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
            $table->string('title')->comment('商品标题');
            $table->string('subtitle')->comment('商品副标题');
            $table->unsignedInteger('if_open')->default(0)->comment('上下架状态');
            $table->unsignedInteger('brand')->comment('品牌');
            $table->string('brand_name')->comment('品牌名称');
            $table->json('categories')->comment('分类');
            $table->string('categories_name')->comment('分类名称');
            $table->json('images')->comment('商品图片');
            $table->string('spu')->unique()->comment('商品SPU');
            $table->decimal('price', 10, 2)->comment('价格');
            $table->text('content')->comment('内容');
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
