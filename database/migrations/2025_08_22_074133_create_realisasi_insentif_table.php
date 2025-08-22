<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealisasiInsentifTable extends Migration
{
    public function up()
    {
        Schema::create('realisasi_insentif', function (Blueprint $table) {
            $table->increments('id_realisasi');
            $table->integer('id_insentif')->unsigned();
            $table->integer('id_karyawan')->unsigned();
            $table->integer('periode_bulan')->nullable();
            $table->integer('periode_tahun')->nullable();
            $table->integer('target_qty')->nullable();
            $table->integer('realisasi_qty')->nullable();
            $table->decimal('target_value', 15, 2)->nullable();
            $table->decimal('realisasi_value', 15, 2)->nullable();
            $table->decimal('persentase_pencapaian', 5, 2)->nullable();
            $table->decimal('nilai_insentif', 15, 2)->nullable();
            $table->enum('status_bayar', ['pending', 'paid'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('realisasi_insentif');
    }
}
