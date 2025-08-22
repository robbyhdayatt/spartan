<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsentifTable extends Migration
{
    public function up()
    {
        Schema::create('insentif', function (Blueprint $table) {
            $table->increments('id_insentif');
            $table->string('nama_program')->nullable();
            $table->integer('id_part')->unsigned()->nullable();
            $table->integer('id_jabatan')->unsigned()->nullable();
            $table->enum('tipe_insentif', ['per_qty', 'per_value', 'percentage'])->nullable();
            $table->decimal('nilai_insentif', 15, 2)->nullable();
            $table->decimal('minimum_target', 15, 2)->nullable();
            $table->integer('jumlah_orang')->nullable();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insentif');
    }
}
