<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('approval_history', function (Blueprint $table) {
            $table->increments('id_approval');
            $table->enum('jenis_dokumen', ['pembelian', 'penjualan', 'retur', 'adjustment', 'receiving'])->nullable();
            $table->integer('id_dokumen')->nullable();
            $table->integer('level_approval')->nullable();
            $table->integer('id_approver')->unsigned()->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->nullable();
            $table->timestamp('tanggal_approval')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval_history');
    }
}
