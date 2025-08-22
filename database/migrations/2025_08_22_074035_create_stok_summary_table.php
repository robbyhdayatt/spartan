<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokSummaryTable extends Migration
{
    public function up()
    {
        Schema::create('stok_summary', function (Blueprint $table) {
            $table->integer('id_part')->unsigned()->primary();
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_reserved')->default(0);
            $table->integer('stok_rusak')->default(0);
            $table->integer('stok_quarantine')->default(0);
            $table->integer('stok_total')->nullable();
            $table->decimal('nilai_stok', 15, 2)->default(0);
            $table->decimal('harga_rata_rata', 15, 2)->default(0);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_summary');
    }
}
