<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('id_penjualan');
            $table->string('nomor_invoice', 50)->unique()->nullable();
            $table->string('nomor_so', 50)->unique()->nullable();
            $table->integer('id_konsumen')->unsigned()->nullable();
            $table->integer('id_sales')->unsigned()->nullable();
            $table->date('tanggal_penjualan')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('ppn_persen', 5, 2)->default(11.00);
            $table->decimal('ppn_amount', 15, 2)->nullable();
            $table->decimal('total_before_tax', 15, 2)->nullable();
            $table->decimal('total_after_tax', 15, 2)->nullable();
            $table->enum('jenis_pengiriman', ['pickup', 'delivery', 'ekspedisi'])->nullable();
            $table->enum('jenis_penjualan', ['cash', 'credit', 'consignment'])->nullable();
            $table->enum('jenis_pembayaran', ['cash', 'transfer', 'giro', 'credit_card'])->nullable();
            $table->decimal('biaya_distribusi', 15, 2)->default(0);
            $table->decimal('biaya_asuransi', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->enum('status_penjualan', ['draft', 'pending_approval', 'approved', 'processed', 'shipped', 'delivered', 'completed', 'cancelled'])->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('status_pembayaran', ['unpaid', 'partial', 'paid', 'overdue'])->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
