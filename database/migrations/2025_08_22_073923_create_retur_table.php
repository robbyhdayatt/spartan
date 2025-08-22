<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturTable extends Migration
{
    public function up()
    {
        Schema::create('retur', function (Blueprint $table) {
            $table->increments('id_retur');
            $table->string('nomor_retur', 50)->unique()->nullable();
            $table->enum('tipe_retur', ['retur_beli', 'retur_jual'])->nullable();
            $table->integer('id_konsumen')->unsigned()->nullable();
            $table->integer('id_supplier')->unsigned()->nullable();
            $table->integer('id_penjualan')->unsigned()->nullable();
            $table->integer('id_pembelian')->unsigned()->nullable();
            $table->integer('id_penerimaan')->unsigned()->nullable();
            $table->date('tanggal_retur')->nullable();
            $table->text('alasan')->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('ppn_amount', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->enum('status_retur', ['draft', 'pending_approval', 'approved', 'processed', 'completed', 'cancelled'])->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retur');
    }
}
