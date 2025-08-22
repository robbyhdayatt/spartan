<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentTable extends Migration
{
    public function up()
    {
        Schema::create('stock_adjustment', function (Blueprint $table) {
            $table->increments('id_adjustment');
            $table->string('nomor_adjustment', 50)->unique()->nullable();
            $table->date('tanggal_adjustment')->nullable();
            $table->enum('jenis_adjustment', ['opname', 'koreksi', 'write_off', 'qc_release'])->nullable();
            $table->integer('id_gudang')->unsigned()->nullable();
            $table->integer('total_selisih_qty')->nullable();
            $table->decimal('total_selisih_value', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_adjustment', ['draft', 'pending_approval', 'approved', 'completed'])->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_adjustment');
    }
}
