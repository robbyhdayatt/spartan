<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaCampaignBeliTable extends Migration
{
    public function up()
    {
        Schema::create('harga_campaign_beli', function (Blueprint $table) {
            $table->increments('id_harga_campaign');
            $table->integer('id_campaign')->unsigned();
            $table->integer('id_supplier')->unsigned();
            $table->integer('id_part')->unsigned()->nullable();
            $table->decimal('harga_campaign', 15, 2)->nullable();
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
        Schema::dropIfExists('harga_campaign_beli');
    }
}
