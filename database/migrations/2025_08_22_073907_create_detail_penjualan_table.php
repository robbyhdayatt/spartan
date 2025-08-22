<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenjualanTable extends Migration
{
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->increments('id_detail_penjualan');
            $table->integer('id_penjualan')->unsigned();
            $table->integer('id_part')->unsigned();
            $table->integer('quantity');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_rupiah', 15, 2)->default(0);
            $table->decimal('total_after_diskon', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
}
