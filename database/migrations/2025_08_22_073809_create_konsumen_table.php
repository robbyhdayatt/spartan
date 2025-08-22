<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonsumenTable extends Migration
{
    public function up()
    {
        Schema::create('konsumen', function (Blueprint $table) {
            $table->increments('id_konsumen');
            $table->string('kode_konsumen', 50)->unique()->nullable();
            $table->string('nama_konsumen');
            $table->text('alamat')->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('npwp', 25)->nullable();
            $table->decimal('limit_kredit', 15, 2)->default(0);
            $table->integer('term_pembayaran')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('konsumen');
    }
}
