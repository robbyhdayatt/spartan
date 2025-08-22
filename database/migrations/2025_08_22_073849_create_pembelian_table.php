<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelianTable extends Migration
{
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->string('nomor_po', 50)->unique()->nullable();
            $table->integer('id_supplier')->unsigned()->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('ppn_persen', 5, 2)->default(11.00);
            $table->decimal('ppn_amount', 15, 2)->nullable();
            $table->decimal('total_before_tax', 15, 2)->nullable();
            $table->decimal('total_after_tax', 15, 2)->nullable();
            $table->decimal('biaya_kirim', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->enum('status_pembelian', ['draft', 'pending_approval', 'approved', 'ordered', 'partial_received', 'received', 'completed', 'cancelled'])->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
}
