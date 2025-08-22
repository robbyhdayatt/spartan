<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id_sales');
            $table->integer('id_karyawan')->unsigned()->nullable();
            $table->integer('id_konsumen')->unsigned()->nullable();
            $table->date('tanggal_assign')->nullable();
            $table->decimal('target_penjualan', 15, 2)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
