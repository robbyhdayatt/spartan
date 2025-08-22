<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailAdjustmentTable extends Migration
{
    public function up()
    {
        Schema::create('detail_adjustment', function (Blueprint $table) {
            $table->increments('id_detail_adjustment');
            $table->integer('id_adjustment')->unsigned();
            $table->integer('id_part')->unsigned();
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->decimal('harga_satuan', 15, 2);
            $table->enum('kondisi_stok', ['baik', 'rusak', 'quarantine'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_adjustment');
    }
}
