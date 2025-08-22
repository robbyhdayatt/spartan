<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanTable extends Migration
{
    public function up()
    {
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->increments('id_penerimaan');
            $table->string('nomor_penerimaan', 50)->unique()->nullable();
            $table->integer('id_pembelian')->unsigned()->nullable();
            $table->date('tanggal_penerimaan')->nullable();
            $table->integer('id_supplier')->unsigned()->nullable();
            $table->integer('id_gudang_tujuan')->unsigned()->nullable();
            $table->string('nomor_surat_jalan', 100)->nullable();
            $table->string('nama_ekspedisi', 100)->nullable();
            $table->string('nomor_kendaraan', 20)->nullable();
            $table->string('nama_pengirim', 100)->nullable();
            $table->integer('total_qty_dipesan')->nullable();
            $table->integer('total_qty_diterima')->nullable();
            $table->integer('total_qty_approved')->default(0);
            $table->integer('total_qty_rejected')->default(0);
            $table->enum('status_penerimaan', ['draft', 'checking', 'qc_pending', 'partial_approved', 'completed', 'rejected'])->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->nullable();
            $table->text('keterangan_penerimaan')->nullable();
            $table->integer('pic_penerima')->unsigned()->nullable();
            $table->integer('qc_by')->unsigned()->nullable();
            $table->date('qc_date')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penerimaan');
    }
}
