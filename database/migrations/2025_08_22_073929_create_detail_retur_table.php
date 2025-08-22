<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailReturTable extends Migration
{
    public function up()
    {
        Schema::create('detail_retur', function (Blueprint $table) {
            $table->increments('id_detail_retur');
            $table->integer('id_retur')->unsigned();
            $table->integer('id_part')->unsigned();
            $table->integer('quantity');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->enum('kondisi_barang', ['baik', 'rusak', 'cacat'])->nullable();
            $table->enum('tindakan', ['ganti', 'refund', 'repair', 'disposal'])->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->string('serial_number')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_retur');
    }
}
