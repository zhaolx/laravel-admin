<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ims_menus_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('菜单标识');
            $table->string('title')->comment('菜单名称');
            $table->string('route')->comment('后台路由');
            $table->string('path')->comment('前端路由');
            $table->string('icon')->comment('图标');
            $table->string('component')->comment('前端组件路径');
            $table->tinyInteger('is_show')->comment('是否显示：0-不显示，1-显示');
            $table->tinyInteger('is_cache')->comment('是否缓存：0-不缓存，1-缓存');
            $table->integer('parent_id')->default(0)->comment('上级ID');
            $table->integer('sort')->nullable();
            $table->tinyInteger('status')->default(0)->comment('状态：-1-删除，0-禁用，1-正常');
            $table->tinyInteger('type')->comment('类型：1-菜单，2-按钮');
            $table->dateTime('in_time');
            $table->dateTime('update_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ims_menus_test');
    }
}
