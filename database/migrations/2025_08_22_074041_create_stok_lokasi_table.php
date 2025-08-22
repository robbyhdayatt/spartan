<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokLokasiTable extends Migration
{
    public function up()
    {
        Schema::create('stok_lokasi', function (Blueprint $table) {
            $table->increments('id_stok_lokasi');
            $table->integer('id_part')->unsigned();
            $table->integer('id_gudang')->unsigned();
            $table->integer('quantity')->default(0);
            $table->integer('quantity_rusak')->default(0);
            $table->integer('quantity_quarantine')->default(0);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_lokasi');
    }
}
