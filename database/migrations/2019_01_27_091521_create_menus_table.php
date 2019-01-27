<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon')->nullable()->comment('菜单图标');
            $table->string('type')->default('_self');
            $table->integer('pid', false, true)->nullable()->comment('父级ID');
            $table->string('url')->default('#')->comment('地址');
            $table->string('name')->comment('菜单名称');
            $table->integer('sort', false, false)->default(0)->comment('排序');
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
        Schema::dropIfExists('menus');
    }
}
