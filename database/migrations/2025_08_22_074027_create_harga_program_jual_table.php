<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaProgramJualTable extends Migration
{
    public function up()
    {
        Schema::create('harga_program_jual', function (Blueprint $table) {
            $table->increments('id_harga_program');
            $table->integer('id_campaign')->unsigned();
            $table->integer('id_konsumen')->unsigned()->nullable();
            $table->integer('id_part')->unsigned()->nullable();
            $table->decimal('harga_program', 15, 2)->nullable();
            $table->integer('minimum_qty')->default(0);
            $table->integer('maksimum_qty')->default(0);
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_program_jual');
    }
}
