<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalLevelTable extends Migration
{
    public function up()
    {
        Schema::create('approval_level', function (Blueprint $table) {
            $table->increments('id_approval_level');
            $table->enum('jenis_dokumen', ['pembelian', 'penjualan', 'retur', 'adjustment', 'receiving'])->nullable();
            $table->integer('level_sequence')->nullable();
            $table->string('nama_level')->nullable();
            $table->decimal('minimum_amount', 15, 2)->default(0);
            $table->integer('id_jabatan_required')->unsigned()->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval_level');
    }
}
