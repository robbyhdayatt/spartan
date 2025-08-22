<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionTable extends Migration
{
    public function up()
    {
        Schema::create('user_permission', function (Blueprint $table) {
            $table->increments('id_permission');
            $table->integer('id_user')->unsigned();
            $table->string('module_name', 100)->nullable();
            $table->boolean('can_create')->default(false);
            $table->boolean('can_read')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permission');
    }
}
