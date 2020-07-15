<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * 用户表
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ims_user_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账号');
            $table->string('password',100)->comment('密码');
            $table->string('nickname')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('role_id')->default(0)->comment('角色ID');
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
        Schema::dropIfExists('ims_user_test');
    }
}
