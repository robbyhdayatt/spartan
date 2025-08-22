<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->increments('id_setting');
            $table->string('key_setting', 100)->unique()->nullable();
            $table->text('value_setting')->nullable();
            $table->enum('data_type', ['string', 'number', 'boolean', 'json'])->nullable();
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->boolean('is_editable')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}
