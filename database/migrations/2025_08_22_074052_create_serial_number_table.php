<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerialNumberTable extends Migration
{
    public function up()
    {
        Schema::create('serial_number', function (Blueprint $table) {
            $table->increments('id_serial');
            $table->integer('id_part')->unsigned()->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->integer('id_gudang')->unsigned()->nullable();
            $table->enum('status_serial', ['available', 'reserved', 'sold', 'damaged', 'returned', 'quarantine'])->nullable();
            $table->integer('id_penjualan')->unsigned()->nullable();
            $table->integer('id_penerimaan')->unsigned()->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('production_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('serial_number');
    }
}
