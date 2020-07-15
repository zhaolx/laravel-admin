<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ims_permission_list_test', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('类型：1-角色，2-用户');
            $table->integer('data_id')->comment('角色/用户ID');
            $table->integer('menu_id')->comment('菜单ID');
            $table->tinyInteger('status')->default(0)->comment('状态：-1-删除，0-禁用，1-正常');
            
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
        Schema::dropIfExists('ims_permission_list_test');
    }
}
