<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKartuStokTable extends Migration
{
    public function up()
    {
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->increments('id_kartu_stok');
            $table->integer('id_part')->unsigned();
            $table->integer('id_gudang')->unsigned();
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->enum('jenis_transaksi', ['pembelian', 'penjualan', 'retur_beli', 'retur_jual', 'adjustment', 'transfer', 'receiving', 'qc_approved', 'qc_rejected'])->nullable();
            $table->string('referensi_dokumen', 100)->nullable();
            $table->integer('referensi_id')->nullable();
            $table->string('nomor_dokumen', 100)->nullable();
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('saldo')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('nilai_transaksi', 15, 2)->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('expired_date')->nullable();
            $table->enum('kondisi_stok', ['baik', 'rusak', 'quarantine'])->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kartu_stok');
    }
}
