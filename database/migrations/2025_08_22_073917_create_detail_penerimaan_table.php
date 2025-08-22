<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenerimaanTable extends Migration
{
    public function up()
    {
        Schema::create('detail_penerimaan', function (Blueprint $table) {
            $table->increments('id_detail_penerimaan');
            $table->integer('id_penerimaan')->unsigned();
            $table->integer('id_detail_pembelian')->unsigned()->nullable();
            $table->integer('id_part')->unsigned();
            $table->integer('qty_dipesan');
            $table->integer('qty_diterima');
            $table->integer('qty_approved')->default(0);
            $table->integer('qty_rejected')->default(0);
            $table->enum('kondisi_barang', ['baik', 'rusak', 'cacat', 'kadaluarsa'])->nullable();
            $table->enum('status_qc', ['pending', 'passed', 'failed', 'conditional', 'skip'])->nullable();
            $table->text('qc_notes')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('production_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('lokasi_simpan', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penerimaan');
    }
}
