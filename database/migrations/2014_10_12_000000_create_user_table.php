<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('username', 100)->unique()->nullable();
            $table->string('password_hash', 255)->nullable();
            $table->integer('id_karyawan')->unsigned()->nullable();
            $table->enum('role_level', ['admin', 'manager', 'supervisor', 'staff', 'viewer'])->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
