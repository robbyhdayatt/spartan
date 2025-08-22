<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartTable extends Migration
{
    public function up()
    {
        Schema::create('part', function (Blueprint $table) {
            $table->increments('id_part');
            $table->string('kode_part', 100)->unique()->nullable();
            $table->string('nama_part');
            $table->integer('id_kategori')->unsigned()->nullable();
            $table->integer('id_brand')->unsigned()->nullable();
            $table->text('spesifikasi')->nullable();
            $table->string('info_kemasan')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->decimal('berat', 10, 2)->nullable();
            $table->integer('minimum_stok')->default(0);
            $table->decimal('harga_pokok', 15, 2)->nullable();
            $table->string('gambar_part')->nullable();
            $table->string('barcode', 100)->nullable();
            $table->boolean('require_qc')->default(false);
            $table->integer('shelf_life_days')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('part');
    }
}
