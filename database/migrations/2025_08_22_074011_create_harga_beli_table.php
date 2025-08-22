<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaBeliTable extends Migration
{
    public function up()
    {
        Schema::create('harga_beli', function (Blueprint $table) {
            $table->increments('id_harga_beli');
            $table->integer('id_part')->unsigned();
            $table->integer('id_supplier')->unsigned();
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->decimal('harga_tebus', 15, 2)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_beli');
    }
}
