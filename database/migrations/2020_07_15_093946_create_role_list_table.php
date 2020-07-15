<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ims_role_list_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('角色名称');
            $table->string('description')->comment('描述');
            $table->integer('sort');
            $table->tinyInteger('id_admin')->default(0)->comment('是否管理员：0-不是，1-是');
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
        Schema::dropIfExists('ims_role_list_test');
    }
}
