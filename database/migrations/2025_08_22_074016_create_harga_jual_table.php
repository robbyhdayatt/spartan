<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaJualTable extends Migration
{
    public function up()
    {
        Schema::create('harga_jual', function (Blueprint $table) {
            $table->increments('id_harga_jual');
            $table->integer('id_part')->unsigned();
            $table->integer('id_konsumen')->unsigned()->nullable();
            $table->decimal('hed', 15, 2)->nullable();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_jual');
    }
}
