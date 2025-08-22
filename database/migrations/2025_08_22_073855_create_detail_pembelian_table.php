<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembelianTable extends Migration
{
    public function up()
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->increments('id_detail_pembelian');
            $table->integer('id_pembelian')->unsigned();
            $table->integer('id_part')->unsigned();
            $table->integer('quantity');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->integer('qty_received')->default(0);
            // Kolom 'qty_remaining' adalah generated column, tidak perlu didefinisikan di migrasi
            // dan akan di-handle oleh database atau accessor di Model.
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
    }
}
