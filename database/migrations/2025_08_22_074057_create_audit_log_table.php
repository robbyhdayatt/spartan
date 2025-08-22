<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogTable extends Migration
{
    public function up()
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->increments('id_log');
            $table->integer('id_user')->unsigned()->nullable();
            $table->string('table_name', 100)->nullable();
            $table->string('record_id')->nullable();
            $table->enum('action_type', ['INSERT', 'UPDATE', 'DELETE', 'SELECT'])->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_log');
    }
}
