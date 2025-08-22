<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTable extends Migration
{
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('id_campaign');
            $table->string('kode_campaign', 50)->unique()->nullable();
            $table->string('nama_campaign')->nullable();
            $table->enum('jenis_campaign', ['purchase', 'sales'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('syarat_minimum', 15, 2)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign');
    }
}
