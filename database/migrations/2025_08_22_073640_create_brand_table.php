<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandTable extends Migration
{
    public function up()
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->increments('id_brand');
            $table->string('nama_brand');
            $table->string('negara_asal', 100)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand');
    }
}
