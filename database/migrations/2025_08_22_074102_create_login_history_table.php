<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('login_history', function (Blueprint $table) {
            $table->increments('id_login');
            $table->integer('id_user')->unsigned()->nullable();
            $table->timestamp('login_time')->useCurrent();
            $table->timestamp('logout_time')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('device_info')->nullable();
            $table->enum('status', ['success', 'failed', 'blocked'])->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_history');
    }
}
