<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangTable extends Migration
{
    public function up()
    {
        Schema::create('gudang', function (Blueprint $table) {
            $table->increments('id_gudang');
            $table->string('kode_gudang', 50)->unique()->nullable();
            $table->string('nama_gudang');
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->integer('id_pic_gudang')->unsigned()->nullable();
            $table->string('telepon', 20)->nullable();
            $table->decimal('kapasitas_maksimal', 15, 2)->nullable();
            $table->enum('jenis_gudang', ['utama', 'transit', 'retur', 'quarantine'])->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gudang');
    }
}
